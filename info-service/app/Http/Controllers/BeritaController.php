<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // Wajib ditambahkan untuk memanipulasi File

class BeritaController extends Controller
{
    // 1. READ: Menampilkan semua berita
    public function index()
    {
        $berita = Berita::orderBy('created_at', 'desc')->get();
        
        // Memodifikasi path menjadi URL lengkap agar gambar bisa langsung tampil di React
        $berita->transform(function ($item) {
            if ($item->gambar_url) {
                $item->gambar_url = url('storage/' . $item->gambar_url);
            }
            return $item;
        });

        return response()->json(['status' => 'success', 'data' => $berita], 200);
    }

    // 2. READ: Menampilkan 1 detail berita
    public function show($id)
    {
        $berita = Berita::find($id);
        
        if (!$berita) {
            return response()->json(['status' => 'error', 'message' => 'Berita tidak ditemukan'], 404);
        }

        if ($berita->gambar_url) {
            $berita->gambar_url = url('storage/' . $berita->gambar_url);
        }

        return response()->json(['status' => 'success', 'data' => $berita], 200);
    }

    // 3. CREATE: Menyimpan berita baru beserta unggahan foto
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            // Validasi file: Harus berupa gambar (jpeg, png, jpg, gif, webp), maksimal 5MB
            'gambar_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', 
            'is_published' => 'nullable|boolean'
        ]);

        $data = $request->except('gambar_url'); // Ambil teksnya, pisahkan filenya
        $data['slug'] = Str::slug($request->judul) . '-' . time(); 

        // Logika menyimpan foto ke server
        if ($request->hasFile('gambar_url')) {
            $path = $request->file('gambar_url')->store('berita_images', 'public');
            $data['gambar_url'] = $path; // Simpan path-nya (contoh: berita_images/foto.jpg)
        }

        $berita = Berita::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Berita berhasil diterbitkan!', 
            'data' => $berita
        ], 201);
    }

    // 4. UPDATE: Mengubah berita & mengganti foto jika ada yang baru
    public function update(Request $request, $id)
    {
        $berita = Berita::find($id);
        
        if (!$berita) {
            return response()->json(['status' => 'error', 'message' => 'Berita tidak ditemukan'], 404);
        }

        $request->validate([
            'judul' => 'sometimes|string|max:255',
            'konten' => 'sometimes|string',
            'gambar_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_published' => 'sometimes|boolean'
        ]);

        $data = $request->except('gambar_url');
        
        if ($request->has('judul') && $request->judul !== $berita->judul) {
            $data['slug'] = Str::slug($request->judul) . '-' . time(); 
        }

        // Jika Admin mengunggah foto baru saat Update
        if ($request->hasFile('gambar_url')) {
            // Hapus foto lama dari hard drive agar tidak nyampah
            if ($berita->gambar_url) {
                Storage::disk('public')->delete($berita->gambar_url);
            }
            // Simpan foto baru
            $path = $request->file('gambar_url')->store('berita_images', 'public');
            $data['gambar_url'] = $path;
        }

        $berita->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Berita berhasil diperbarui!',
            'data' => $berita
        ], 200);
    }

    // 5. DELETE: Menghapus berita dan membuang foto fisiknya
    public function destroy($id)
    {
        $berita = Berita::find($id);
        
        if (!$berita) {
            return response()->json(['status' => 'error', 'message' => 'Berita tidak ditemukan'], 404);
        }

        // Hapus file fisik gambar dari folder storage sebelum datanya dihapus
        if ($berita->gambar_url) {
            Storage::disk('public')->delete($berita->gambar_url);
        }

        $berita->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berita berhasil dihapus!'
        ], 200);
    }
}