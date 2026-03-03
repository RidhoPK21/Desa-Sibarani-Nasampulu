<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdmController;
use App\Http\Controllers\DusunController;

// Endpoint Indeks Desa Membangun (IDM)
Route::get('/idm', [IdmController::class, 'index']);
Route::post('/idm', [IdmController::class, 'store']);

// Endpoint Demografi Dusun
Route::get('/dusun', [DusunController::class, 'index']);
Route::post('/dusun', [DusunController::class, 'store']);