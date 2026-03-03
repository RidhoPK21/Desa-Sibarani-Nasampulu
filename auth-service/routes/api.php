<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Tambahkan ini di atas

// Rute untuk Login (Bisa diakses siapa saja / public)
Route::post('/login', [AuthController::class, 'login']);

// Rute contoh yang butuh token (Harus login dulu)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');