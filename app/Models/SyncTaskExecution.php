<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SyncTaskExecution extends Model
{

    protected $table = 'sync_task_executions';

    protected $fillable = [
        'task_id',
        'task_name',
        'profile_id',
        'profile_name',
        'source_db',
        'destination_db',
        'executed_records',
        'success_count',
        'fail_count',
        'status',
        'error_message',
        'started_at',
        'finished_at',
        'elapsed_time_ms',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if ($model->started_at && $model->finished_at) {
                $started = $model->started_at instanceof Carbon
                    ? $model->started_at
                    : Carbon::parse($model->started_at);

                $finished = $model->finished_at instanceof Carbon
                    ? $model->finished_at
                    : Carbon::parse($model->finished_at);

                $model->elapsed_time_ms = $finished->diffInMilliseconds($started);
            } else {
                $model->elapsed_time_ms = null;
            }
        });
    }
}
