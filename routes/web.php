<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/status', function () {
    // Ovde kasnije dodaš prikaz statusa bridge konekcije
    return response()->json(['erp' => 'connected', 'last_sync' => now()]);
})->name('status');
