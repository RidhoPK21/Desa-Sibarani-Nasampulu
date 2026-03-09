<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// 🌍 Rute Terbuka (Siapa saja bisa mencoba login)
Route::post('/login', [AuthController::class, 'login']);

// 🔐 Rute Tertutup (HANYA BISA DIAKSES JIKA BAWA TOKEN)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

});