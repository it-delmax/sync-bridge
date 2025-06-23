<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncTaskExecution extends Model
{

    protected $table = 'sync_task_executions';

    protected $fillable = [
        'batch_id',
        'task_id',
        'task_name',
        'profile_id',
        'profile_name',
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

    public function markCompleted(string $status = 'completed'): void
    {
        $this->fill([
            'finished_at' => now(),
            'status' => $status,
        ])->save();
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(SyncBatch::class, 'batch_id', 'batch_id');
    }
}
