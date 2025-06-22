<?php

namespace App\Models;

/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 4.8.2016
 * Time: 23:16
 */


class UpdateKind
{
    const INSERT = 0;
    const UPDATE = 1;
    const DELETE = 2;
    const UPDATE_OR_INSERT = 3;
    const BATCH_INSERT = 8;
    const NO_DB_ACTION = 12;
}
