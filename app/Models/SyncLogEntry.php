<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLogEntry extends Model
{
    protected $connection = 'dmxsync';
    protected $table = 'sync_log';
    public $timestamps = false;

    protected $fillable = [
        'row_id',
        'table_name',
        'task_id',
        'success',
        'error_message',
        'source',
        'synced_by',
        'attempt_no',
        'created_at',
    ];
}
