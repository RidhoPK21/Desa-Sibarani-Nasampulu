<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;

// 🌍 RUTE PUBLIK (Untuk Website Warga - Hanya menampilkan yang shown = true)
Route::get('/banner/public', [BannerController::class, 'indexPublic']);


// 🔐 RUTE ADMIN (Untuk Dashboard Manajemen)
Route::get('/banner', [BannerController::class, 'indexAdmin']); // Tampil semua
Route::get('/banner/{id}', [BannerController::class, 'show']);
Route::post('/banner', [BannerController::class, 'store']);
Route::put('/banner/{id}', [BannerController::class, 'update']);
Route::post('/banner/{id}', [BannerController::class, 'update']); // Fallback upload file gambar
Route::delete('/banner/{id}', [BannerController::class, 'destroy']);