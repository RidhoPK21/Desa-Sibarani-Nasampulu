<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdmController;
use App\Http\Controllers\DusunController;

// ==========================================
// 🌍 RUTE PUBLIK (Dapat diakses oleh Warga / Frontend)
// Fungsi: Hanya untuk melihat data (GET)
// ==========================================

// --- IDM (Indeks Desa Membangun) ---
Route::get('/idm', [IdmController::class, 'index']);
Route::get('/idm/{id}', [IdmController::class, 'show']);

// --- Demografi Dusun ---
Route::get('/dusun', [DusunController::class, 'index']);
Route::get('/dusun/{id}', [DusunController::class, 'show']);


// ==========================================
// 🔐 RUTE ADMIN (Untuk Menambah, Mengubah, Menghapus)
// Fungsi: POST, PUT, DELETE
// ==========================================

// --- IDM (Indeks Desa Membangun) ---
Route::post('/idm', [IdmController::class, 'store']);
Route::put('/idm/{id}', [IdmController::class, 'update']);
Route::delete('/idm/{id}', [IdmController::class, 'destroy']);

// --- Demografi Dusun ---
Route::post('/dusun', [DusunController::class, 'store']);
Route::put('/dusun/{id}', [DusunController::class, 'update']);
Route::delete('/dusun/{id}', [DusunController::class, 'destroy']);