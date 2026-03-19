<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApbdesController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\ProfilDesaController;

// ==========================================
// 🌍 RUTE PUBLIK (Dapat diakses oleh Warga)
// Fungsi: Hanya untuk melihat data (GET)
// ==========================================

// --- Berita ---
Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/{id}', [BeritaController::class, 'show']);

// --- Dokumen (PPID) ---
Route::get('/dokumen', [DokumenController::class, 'index']);
Route::get('/dokumen/{id}', [DokumenController::class, 'show']);

// --- Kegiatan ---
Route::get('/kegiatan', [KegiatanController::class, 'index']);
Route::get('/kegiatan/{id}', [KegiatanController::class, 'show']);

// --- APBDes ---
Route::get('/apbdes', [ApbdesController::class, 'index']); // Menampilkan versi yang aktif
Route::get('/apbdes/riwayat/{tahun}', [ApbdesController::class, 'riwayat']); // Riwayat perubahan
Route::get('/apbdes/{id}', [ApbdesController::class, 'show']);

// --- Profil Desa ---
Route::get('/profil/kata-sambutan', [ProfilDesaController::class, 'indexKataSambutan']);
Route::get('/profil/kata-sambutan/{id}', [ProfilDesaController::class, 'showKataSambutan']);

Route::get('/profil/visi-misi', [ProfilDesaController::class, 'indexVisiMisi']);
Route::get('/profil/visi-misi/{id}', [ProfilDesaController::class, 'showVisiMisi']);

Route::get('/profil/perangkat-desa', [ProfilDesaController::class, 'indexPerangkatDesa']);
Route::get('/profil/perangkat-desa/{id}', [ProfilDesaController::class, 'showPerangkatDesa']);


// ==========================================
// 🔐 RUTE ADMIN (Untuk Menambah, Mengubah, Menghapus)
// Fungsi: POST, PUT, DELETE
// ==========================================

// --- Berita ---
Route::post('/berita', [BeritaController::class, 'store']);
Route::put('/berita/{id}', [BeritaController::class, 'update']);
Route::post('/berita/{id}', [BeritaController::class, 'update']); // Fallback upload file
Route::delete('/berita/{id}', [BeritaController::class, 'destroy']);

// --- Dokumen ---
Route::post('/dokumen', [DokumenController::class, 'store']);
Route::put('/dokumen/{id}', [DokumenController::class, 'update']);
Route::post('/dokumen/{id}', [DokumenController::class, 'update']); // Fallback upload file
Route::delete('/dokumen/{id}', [DokumenController::class, 'destroy']);

// --- Kegiatan ---
Route::post('/kegiatan', [KegiatanController::class, 'store']);
Route::put('/kegiatan/{id}', [KegiatanController::class, 'update']);
Route::post('/kegiatan/{id}', [KegiatanController::class, 'update']); // Fallback upload file
Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy']);

// --- APBDes ---
Route::post('/apbdes', [ApbdesController::class, 'store']);
Route::put('/apbdes/{id}', [ApbdesController::class, 'update']);
Route::delete('/apbdes/{id}', [ApbdesController::class, 'destroy']);
Route::get('/apbdes/riwayat/{tahun}', [ApbdesController::class, 'riwayat']); // Fitur riwayat

// --- Profil Desa (Kata Sambutan) ---
Route::post('/profil/kata-sambutan', [ProfilDesaController::class, 'storeKataSambutan']);
Route::put('/profil/kata-sambutan/{id}', [ProfilDesaController::class, 'updateKataSambutan']);
Route::delete('/profil/kata-sambutan/{id}', [ProfilDesaController::class, 'destroyKataSambutan']);

// --- Profil Desa (Visi Misi) ---
Route::post('/profil/visi-misi', [ProfilDesaController::class, 'storeVisiMisi']);
Route::put('/profil/visi-misi/{id}', [ProfilDesaController::class, 'updateVisiMisi']);
Route::delete('/profil/visi-misi/{id}', [ProfilDesaController::class, 'destroyVisiMisi']);

// --- Profil Desa (Perangkat Desa) ---
Route::post('/profil/perangkat-desa', [ProfilDesaController::class, 'storePerangkatDesa']);
Route::put('/profil/perangkat-desa/{id}', [ProfilDesaController::class, 'updatePerangkatDesa']);
Route::post('/profil/perangkat-desa/{id}', [ProfilDesaController::class, 'updatePerangkatDesa']); // Fallback upload file
Route::delete('/profil/perangkat-desa/{id}', [ProfilDesaController::class, 'destroyPerangkatDesa']);