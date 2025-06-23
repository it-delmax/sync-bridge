<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('dmx:sync-profile')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->onOneServer(); // ako koristiš više servera

Schedule::command('dmx:delete-sync2-tables --days=2')
    ->daily()
    ->withoutOverlapping()
    ->at('01:00') // vreme kada će se izvršiti
    ->onOneServer(); // ako koristiš više servera
