<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sync Profile Configuration
    |--------------------------------------------------------------------------
    |
    | SYNC_PROFILE_NAME - Naziv profila koji se koristi za pokretanje taskova
    | SYNC_TARGET_CONNECTION - Naziv konekcije ka ciljanom sistemu (npr. mysql konekcija)
    |
    */

    'profile_name' => env('SYNC_PROFILE_NAME', null),
    'target_connection' => env('SYNC_TARGET_CONNECTION', null),
];
