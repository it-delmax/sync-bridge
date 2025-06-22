<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\UpdateKind;
use App\Models\SyncLog;
use App\Models\TaskField;
use App\Models\Profile;
use App\Services\SyncRecordLogger;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.8.2016
 * Time: 6:24sta
 */


class Task extends Model
{
    protected $connection = 'dmxsync';

    protected $table = 'task';

    protected $primaryKey = 'task_id';

    public $dstSQLStatement = '';

    public $srcSQLStatement = '';

    public $srcData;

    private $fieldMap = [];

    private $params = [];

    public function profile()
    {

        return $this->belongsTo(Profile::class, 'profile_id', 'profile_id');
    }

    public function fields()
    {
        return $this->hasMany(TaskField::class, 'task_id', 'task_id')->orderBy('order_index');
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id', 'task_id');
    }

    /**
     * @param array $range
     * @return array ['success' => int, 'failed' => int]
     */
    public function execute(array $range = []): array
    {
        $logger = new SyncRecordLogger(
            connection: $this->profile->srcResource->log_connection,
            source: $this->profile->srcResource->name ?? 'unknown',
            syncedBy: 'cron'
        );

        $this->prepareTask($range);

        switch ($this->profile->dstResource->resourceType->name) {
            case 'MySQL':
                $recordsCount = $this->executeMySQL($logger);
                break;
            case 'ElasticSearch':
                $recordsCount  = $this->executeEs($logger);
                break;
            default:
                $recordsCount  = ['success' => 0, 'failed' => 0];
                break;
        }

        return $recordsCount;
    }

    public function executeMySQL(SyncRecordLogger $logger)
    {
        $success = 0;
        $failed = 0;
        $connection = $this->profile->dstResource->db_connection;

        $this->dstSQLStatement = $this->buildSQLStatemnt();

        $i = 0;

        foreach ($this->srcData as $rec) {

            $statement = $this->dstSQLStatement;


            $paramsWithValues = $this->mapValuesToParams($rec);

            try {

                DB::connection($connection)->statement($statement, $paramsWithValues);

                $logger->logSuccessful($this->dst_table, $rec->rownum, $this->task_id);

                $success++;
            } catch (Exception $e) {

                $logger->logFailed($this->dst_table, $rec->rownum, $this->task_id, $e->getMessage());
                Log::error($e->getMessage());
                $failed++;
            }
        }
        return ['success' => $success, 'failed' => $failed];
    }

    private function prepareTask(array $range = [])
    {

        $this->srcSQLStatement = str_replace(':task_id', $this->task_id, $this->src_query);

        $this->srcData = $this->getSrcData($range);
    }

    private function buildFieldMap()
    {

        $this->fieldMap = [];

        foreach ($this->fields as $field) {

            $this->fieldMap[$field->dst_field_name] = $field->src_field_name;
        }
    }

    private function buildSQLStatemnt()
    {
        $this->buildFieldMap();

        switch ($this->update_kind_id) {
            case UpdateKind::INSERT:
                return sprintf('INSERT INTO `%s` %s;', $this->dst_table, $this->buildInsFields());
                break;
            case UpdateKind::UPDATE:
                return sprintf('UPDATE `%s` SET %s %s;', $this->dst_table, $this->buildUpdFields(), $this->buildWhere());
                break;
            case UpdateKind::UPDATE_OR_INSERT:
                return sprintf('INSERT INTO `%s` %s ON DUPLICATE KEY UPDATE %s;', $this->dst_table, $this->buildInsFields(), $this->buildOnDuplicateUpdateFields());
                break;
            default:
                return '';
                break;
        }
    }

    private function buildInsFields()
    {
        $sFields = '';
        $sParams = '';

        foreach ($this->fields as $field) {
            $sFields .= ' ' . $field->dst_field_name . ',';
            $sParams .= ' :' . $field->dst_field_name . ',';
            $this->params[$field->dst_field_name] = null;
        }

        $sFields = substr($sFields, 0, -1);
        $sParams = substr($sParams, 0, -1);

        return sprintf('(%s) VALUES (%s)', $sFields, $sParams);
    }

    private function buildOnDuplicateUpdateFields()
    {
        $sFields = '';

        foreach ($this->fields as $field) {

            if (($field->is_update == 1) and !($field->is_where == 1)) {
                $sFields .= $field->dst_field_name . '= values(' . $field->dst_field_name . '),';
            }
        }

        $sFields = substr($sFields, 0, -1);

        return $sFields;
    }

    private function buildUpdFields()
    {
        $sFields = '';

        foreach ($this->fields as $field) {

            if (($field->is_update == 1) and !($field->is_where == 1)) {
                $sFields .= $field->dst_field_name . '=:' . $field->dst_field_name . ',';
                $this->params[$field->dst_field_name] = null;
            }
        }

        $sFields = substr($sFields, 0, -1);

        return $sFields;
    }

    private function buildWhere()
    {
        $sFields = '';
        $result = '';

        foreach ($this->fields as $field) {

            if ($field->is_where == 1) {

                $sFields .= $field->dst_field_name . '=:' . $field->dst_field_name . ' AND ';

                $this->params[$field->dst_field_name] = null;
            }
        }

        $sFields = substr($sFields, 0,  -5);

        if (!empty($sFields))
            $result .= 'WHERE ' . $sFields;

        return $result;
    }

    private function getSrcData(array $range = [])
    {
        $connection = $this->profile->srcResource->db_connection;

        return DB::connection($connection)->select($this->srcSQLStatement, $range);
    }

    private function mapValuesToParams(object $srcRecord): array
    {
        $result = [];

        foreach ($this->fieldMap as $dst => $src) {
            $result[$dst] = $srcRecord->$src ?? null;
        }

        return $result;
    }
}
