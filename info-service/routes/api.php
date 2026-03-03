<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApbdesController;
use App\Http\Controllers\BeritaController; // Tambahkan di bagian atas
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\ProfilDesaController;

// Rute untuk Publik (Siapa saja bisa melihat data APBDes)
Route::get('/apbdes', [ApbdesController::class, 'index']);

// Rute untuk Admin (Menyimpan data APBDes baru)
Route::post('/apbdes', [ApbdesController::class, 'store']);

Route::get('/berita', [BeritaController::class, 'index']);
Route::post('/berita', [BeritaController::class, 'store']);

Route::get('/dokumen', [DokumenController::class, 'index']);
Route::get('/kegiatan', [KegiatanController::class, 'index']);
Route::get('/profil/kata-sambutan', [ProfilDesaController::class, 'getKataSambutan']);
Route::get('/profil/visi-misi', [ProfilDesaController::class, 'getVisiMisi']);
Route::get('/profil/perangkat-desa', [ProfilDesaController::class, 'getPerangkatDesa']);

// RUTE ADMIN (Untuk menambah/mengubah data)
Route::post('/dokumen', [DokumenController::class, 'store']);
Route::post('/kegiatan', [KegiatanController::class, 'store']);
Route::post('/profil/kata-sambutan', [ProfilDesaController::class, 'storeKataSambutan']);
Route::post('/profil/visi-misi', [ProfilDesaController::class, 'storeVisiMisi']);
Route::post('/profil/perangkat-desa', [ProfilDesaController::class, 'storePerangkatDesa']);