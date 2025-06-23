<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncLogEntry extends Model
{

    protected $table = 'sync_log';

    public $timestamps = false;

    protected $fillable = [
        'row_id',
        'batch_id',
        'table_name',
        'task_id',
        'success',
        'error_message',
        'source',
        'synced_by',
        'attempt_no',
        'created_at',
    ];


    public function batch(): BelongsTo
    {
        return $this->belongsTo(SyncBatch::class, 'batch_id', 'batch_id');
    }
}
