<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use App\Models\Resource;


class DynamicConnection
{
    public static function makeFromResource(Resource $resource, string $connectionFieldName, ?string $connectionName = null): string
    {
        // Parsiraj parametre iz polja
        $paramString = $resource->{$connectionFieldName};

        if (empty($paramString)) {
            throw new \Exception("Connection params field '$connectionFieldName' is empty.");
        }

        $params = self::parseConnectionParams($paramString);

        if (empty($params)) {
            throw new \Exception("Failed to parse connection parameters from field '$connectionFieldName'.");
        }

        $driverId = strtolower($params['DriverID'] ?? 'mysql');

        if (!$connectionName) {
            $connectionName = $connectionName = 'dynamic_' . $resource->resource_id;
        }


        // Osnovni parametri koje koristi većina drajvera
        $host     = $params['server'] ?? '127.0.0.1';
        $database = $params['database'] ?? '';
        $username = $params['user_name'] ?? '';
        $password = $params['password'] ?? '';
        $charset  = $params['CharacterSet'] ?? 'utf8';
        $collation = $params['Collation'] ?? 'utf8_general_ci';

        switch ($driverId) {
            case 'mysql':
                Config::set("database.connections.$connectionName", [
                    'driver'    => 'mysql',
                    'host'      => $host,
                    'port'      => $params['port'] ?? 3306,
                    'database'  => $database,
                    'username'  => $username,
                    'password'  => $password,
                    'charset'   => $charset,
                    'collation' => $collation,
                    'prefix'    => '',
                    'strict'    => true,
                    'engine'    => null,
                    'options'   => extension_loaded('pdo_mysql') ? array_filter([]) : [],
                ]);
                break;

            case 'mssql':
                Config::set("database.connections.$connectionName", [
                    'driver'    => 'sqlsrv',
                    'host'      => $host,
                    'port'      => $params['port'] ?? 1433,
                    'database'  => $database,
                    'username'  => $username,
                    'password'  => $password,
                    'charset'   => $charset,
                    'prefix'    => '',
                    'trust_server_certificate' => true,
                    'options'   => [],
                ]);
                break;

            case 'pgsql':
                Config::set("database.connections.$connectionName", [
                    'driver'   => 'pgsql',
                    'host'     => $host,
                    'port'     => $params['port'] ?? 5432,
                    'database' => $database,
                    'username' => $username,
                    'password' => $password,
                    'charset'  => $charset,
                    'prefix'   => '',
                    'schema'   => $params['schema'] ?? 'public',
                    'sslmode'  => 'prefer',
                ]);
                break;

            case 'firebird':
                Config::set("database.connections.$connectionName", [
                    'driver'   => 'firebird',
                    'host'     => $host,
                    'database' => $database,
                    'username' => $username,
                    'password' => $password,
                    'charset'  => $charset,
                    'role'     => $params['role'] ?? null,
                    'dialect'  => $params['dialect'] ?? 3,
                ]);
                break;

            default:
                throw new \Exception("Nepodržani driver: $driverId");
        }

        return $connectionName;
    }

    public static function parseConnectionParams(string $input): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($input));
        $params = [];

        foreach ($lines as $line) {
            $line = trim($line, " \t\n\r\0\x0B;"); // uklanja i ; i razmake sa krajeva

            if ($line === '' || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);

            $params[trim($key)] = trim($value, " \t\n\r\0\x0B;");
        }
        return $params;
    }
}
