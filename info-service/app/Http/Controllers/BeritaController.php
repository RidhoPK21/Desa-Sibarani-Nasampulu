<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    // 1. Menampilkan semua berita & pengumuman
    public function index()
    {
        $berita = Berita::orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $berita], 200);
    }

    // 2. Menyimpan berita baru
    // 2. Menyimpan berita baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
            // kategori dihapus dari sini
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->judul) . '-' . time(); 

        $berita = Berita::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Berita berhasil diterbitkan!', // Pesan disesuaikan
            'data' => $berita
        ], 201);
    }
}