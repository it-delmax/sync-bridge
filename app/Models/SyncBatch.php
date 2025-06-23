<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SyncBatch extends Model
{


    protected $table = 'sync_batches';
    protected $primaryKey = 'batch_id';

    protected $fillable = [
        'profile_id',
        'profile_name',
        'source_db',
        'destination_db',
        'started_at',
        'success_count',
        'fail_count',
        'executed_records',
        'finished_at',
        'elapsed_time_ms',
        'status',
        'error_message',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function executions(): HasMany
    {
        return $this->hasMany(SyncTaskExecution::class, 'batch_id', 'batch_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SyncLogEntry::class, 'batch_id', 'batch_id');
    }
}
