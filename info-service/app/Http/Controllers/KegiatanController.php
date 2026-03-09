<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Wajib untuk manajemen file gambar

class KegiatanController extends Controller
{
    // 1. READ: Menampilkan semua kegiatan (Diurutkan dari yang terdekat/paling awal)
    public function index()
    {
        $kegiatan = Kegiatan::orderBy('tanggal_pelaksana', 'asc')->get();
        
        // Memodifikasi path menjadi URL lengkap agar gambar bisa tampil di Frontend
        $kegiatan->transform(function ($item) {
            if ($item->gambar) {
                $item->gambar_url = url('storage/' . $item->gambar);
            }
            return $item;
        });

        return response()->json(['status' => 'success', 'data' => $kegiatan], 200);
    }

    // 2. READ: Menampilkan 1 detail kegiatan
    public function show($id)
    {
        $kegiatan = Kegiatan::find($id);
        
        if (!$kegiatan) {
            return response()->json(['status' => 'error', 'message' => 'Kegiatan tidak ditemukan'], 404);
        }

        if ($kegiatan->gambar) {
            $kegiatan->gambar_url = url('storage/' . $kegiatan->gambar);
        }

        return response()->json(['status' => 'success', 'data' => $kegiatan], 200);
    }

    // 3. CREATE: Menambah kegiatan baru beserta poster/gambar
    public function store(Request $request)
    {
        $request->validate([
            'jenis_kegiatan' => 'required|in:Rapat,Gotong Royong,Sosialisasi,Perayaan,Lainnya',
            'judul_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'nullable|string',
            // Validasi file: Harus berupa gambar, maksimal 5MB
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tanggal_pelaksana' => 'required|date',
            // Validasi pintar: Tanggal berakhir tidak boleh mendahului tanggal pelaksana
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_pelaksana', 
            'status_kegiatan' => 'nullable|in:Akan Datang,Berlangsung,Selesai,Batal' // Default: Akan Datang di DB
        ]);

        $data = $request->except('gambar');

        // Menyimpan file gambar fisik ke folder storage/app/public/kegiatan_images
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('kegiatan_images', 'public');
            $data['gambar'] = $path;
        }

        $kegiatan = Kegiatan::create($data);

        return response()->json([
            'status' => 'success', 
            'message' => 'Kegiatan berhasil ditambahkan!', 
            'data' => $kegiatan
        ], 201);
    }

    // 4. UPDATE: Mengubah data kegiatan & mengganti poster jika diupload yang baru
    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::find($id);
        
        if (!$kegiatan) {
            return response()->json(['status' => 'error', 'message' => 'Kegiatan tidak ditemukan'], 404);
        }

        $request->validate([
            'jenis_kegiatan' => 'sometimes|in:Rapat,Gotong Royong,Sosialisasi,Perayaan,Lainnya',
            'judul_kegiatan' => 'sometimes|string|max:255',
            'deskripsi_kegiatan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tanggal_pelaksana' => 'sometimes|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_pelaksana',
            'status_kegiatan' => 'sometimes|in:Akan Datang,Berlangsung,Selesai,Batal'
        ]);

        $data = $request->except('gambar');

        // Jika Admin mengunggah gambar baru saat Update
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari server
            if ($kegiatan->gambar) {
                Storage::disk('public')->delete($kegiatan->gambar);
            }
            // Simpan gambar baru
            $path = $request->file('gambar')->store('kegiatan_images', 'public');
            $data['gambar'] = $path;
        }

        $kegiatan->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Kegiatan berhasil diperbarui!',
            'data' => $kegiatan
        ], 200);
    }

    // 5. DELETE: Menghapus kegiatan dan posternya
    public function destroy($id)
    {
        $kegiatan = Kegiatan::find($id);
        
        if (!$kegiatan) {
            return response()->json(['status' => 'error', 'message' => 'Kegiatan tidak ditemukan'], 404);
        }

        // Hapus file fisik gambar jika ada
        if ($kegiatan->gambar) {
            Storage::disk('public')->delete($kegiatan->gambar);
        }

        $kegiatan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kegiatan berhasil dihapus!'
        ], 200);
    }
}