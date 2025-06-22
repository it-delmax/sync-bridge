<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Resource;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.8.2016
 * Time: 6:21
 */


class Profile extends Model
{
    protected $connection = 'dmxsync';

    protected $table = 'profile';

    protected $primaryKey = 'profile_id';

    public function activeTasks()
    {

        return $this->hasMany(Task::class, 'profile_id', 'profile_id')->where('is_active', 1)->orderBy('order_index');
    }

    public function srcResource()
    {

        return $this->hasOne(Resource::class, 'resource_id', 'src_resource_id');
    }

    public function dstResource()
    {

        return $this->hasOne(Resource::class, 'resource_id', 'dst_resource_id');
    }

    public static function executeByName($name) {}
}
