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
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:Berita,Pengumuman',
            'konten' => 'required',
        ]);

        // Otomatis membuat slug dari judul (contoh: "Rapat Desa" -> "rapat-desa")
        $data = $request->all();
        $data['slug'] = Str::slug($request->judul) . '-' . time(); // Tambah time() agar selalu unik

        $berita = Berita::create($data);

        return response()->json([
            'status' => 'success',
            'message' => $request->kategori . ' berhasil diterbitkan!',
            'data' => $berita
        ], 201);
    }
}