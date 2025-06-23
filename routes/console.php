<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('dmx:sync-profile etg_api_production')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->onOneServer(); // ako koristiš više servera
