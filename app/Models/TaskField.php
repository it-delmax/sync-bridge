<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.8.2016
 * Time: 6:49
 */


class TaskField extends Model
{

    protected $connection = 'dmxsync';

    protected $table = 'task_field';

    protected $primaryKey = 'task_field_id';
}
