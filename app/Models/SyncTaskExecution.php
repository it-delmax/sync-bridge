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

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    // protected static function booted(): void
    // {
    //     static::saving(function (self $model) {
    //         try {
    //             $started = $model->started_at instanceof Carbon
    //                 ? $model->started_at
    //                 : Carbon::parse($model->started_at);

    //             $finished = $model->finished_at instanceof Carbon
    //                 ? $model->finished_at
    //                 : Carbon::parse($model->finished_at);

    //             if ($started && $finished) {
    //                 $model->elapsed_time_ms = max(0, $finished->diffInMilliseconds($started));
    //             }
    //         } catch (\Throwable $e) {
    //             logger()->warning('Elapsed time calc failed', [
    //                 'started_at' => $model->started_at,
    //                 'finished_at' => $model->finished_at,
    //                 'error' => $e->getMessage(),
    //             ]);
    //             $model->elapsed_time_ms = null;
    //         }
    //     });
    // }

    public function markCompleted(string $status = 'completed'): void
    {
        $this->fill([
            'finished_at' => now(),
            'status' => $status,
        ])->save();
    }
}
