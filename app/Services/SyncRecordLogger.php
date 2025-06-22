<?php

namespace App\Services;

use App\Models\SyncLogEntry;

class SyncRecordLogger
{
    public function __construct(
        protected string $connection,
        protected ?string $source = 'unknown',
        protected ?string $syncedBy = null
    ) {}

    public function logSuccessful(string $table, int $rowId, int $taskId): void
    {
        $this->logInternal($table, $rowId, $taskId, 1);
    }

    public function logFailed(string $table, int $rowId, int $taskId, ?string $error = null): void
    {
        $this->logInternal($table, $rowId, $taskId, 0, $error);
    }

    protected function logInternal(string $table, int $rowId, int $taskId, int $success, ?string $error = null): SyncLogEntry
    {
        $attemptNo = SyncLogEntry::on($this->connection)
            ->where('row_id', $rowId)
            ->where('task_id', $taskId)
            ->max('attempt_no') + 1 ?? 1;

        return SyncLogEntry::on($this->connection)->create([
            'row_id'        => $rowId,
            'table_name'    => $table,
            'task_id'       => $taskId,
            'success'       => $success,
            'error_message' => $error,
            'source'        => $this->source,
            'synced_by'     => $this->syncedBy ?? (app()->runningInConsole() ? 'artisan' : 'api'),
            'attempt_no'    => $attemptNo,
            'created_at'    => now(),
        ]);
    }
}
