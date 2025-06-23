<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncBatch extends Model
{
    protected $fillable = [
        'profile_id',
        'profile_name',
        'source_db',
        'destination_db',
        'started_at',
        'finished_at',
        'elapsed_time_ms',
        'status',
        'error_message',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function executions()
    {
        return $this->hasMany(SyncTaskExecution::class, 'batch_id');
    }
}
