<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResourceType;
use App\Services\DynamicConnection;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.8.2016
 * Time: 22:51
 */


class Resource extends Model
{
    protected $connection = 'dmxsync';
    protected $table = 'resource';
    protected $primaryKey = 'resource_id';


    public function resourceType()
    {

        return $this->hasOne(ResourceType::class, 'resource_type_id', 'resource_type_id');
    }

    public function getLogDbName()
    {
        $connection = $this->getLogConnectionAttribute();

        $config = config("database.connections.$connection");

        if ($config) {
            $host = $config['host'] ?? 'localhost';
            $database = $config['database'] ?? '';

            return "$host:$database";
        }

        return '';
    }

    public function getDbName(): string
    {
        $connection = $this->getDbConnectionAttribute();

        $config = config("database.connections.$connection");

        if ($config) {
            $host = $config['host'] ?? 'localhost';
            $database = $config['database'] ?? '';

            return "$host:$database";
        }

        return '';
    }

    public function getDbConnectionAttribute()
    {
        $connectionName = 'dynamic_' . $this->id;

        if (!config()->has("database.connections.{$connectionName}")) {
            DynamicConnection::makeFromResource($this, 'db_connection_params');
        }

        return $connectionName;
    }

    public function getLogConnectionAttribute()
    {
        $connectionName = 'log_' . $this->id;

        if (!config()->has("database.connections.{$connectionName}")) {
            DynamicConnection::makeFromResource($this, 'log_connection_params', $connectionName);
        }

        return $connectionName;
    }
}
