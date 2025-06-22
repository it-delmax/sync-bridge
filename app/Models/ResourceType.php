<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.8.2016
 * Time: 23:09
 */


class ResourceType extends Model
{
    protected $connection = 'dmxsync';

    protected $table = 'resource_type';

    protected $primaryKey = 'resource_type_id';
}
