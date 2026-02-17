<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;

Route::get('/', function () {
    return view('welcome');
});

// Endpoint utama UAS
Route::get('/list', [SensorController::class, 'list']);

// Endpoint JSON alternatif
Route::get('/api/list', [SensorController::class, 'listJson']);
