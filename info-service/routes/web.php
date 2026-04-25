<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', function () {
    return view('welcome');
});

// 🔥 Route untuk melayani file dari storage (gambar berita, dokumen, dll)
Route::get('/storage/{path}', function ($path) {
    $fullPath = 'public/' . $path;
    
    // Cegah directory traversal attack
    if (strpos(realpath(storage_path($fullPath)), realpath(storage_path('public'))) !== 0) {
        abort(403, 'Unauthorized');
    }
    
    if (!Storage::exists($fullPath)) {
        abort(404, 'File not found');
    }
    
    return response()->file(
        storage_path($fullPath),
        ['Content-Type' => mime_content_type(storage_path($fullPath))]
    );
})->where('path', '.*');
