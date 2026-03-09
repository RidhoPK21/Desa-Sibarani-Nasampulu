<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Wajib untuk upload gambar

class BannerController extends Controller
{
    // ==========================================
    // 🌍 AREA PUBLIK (Untuk Website Warga)
    // ==========================================
    
    // 1. Tampilkan HANYA banner yang aktif (shown = true)
    public function indexPublic() {
        $banners = Banner::where('shown', true)->orderBy('urutan', 'asc')->get();
        
        $banners->transform(function ($item) {
            if ($item->gambar_banner) {
                $item->gambar_url = url('storage/' . $item->gambar_banner);
            }
            return $item;
        });

        return response()->json(['status' => 'success', 'data' => $banners], 200);
    }


    // ==========================================
    // 🔐 AREA ADMIN (Untuk Dashboard React)
    // ==========================================

    // 2. Tampilkan SEMUA banner (termasuk yang disembunyikan)
    public function indexAdmin() {
        $banners = Banner::orderBy('urutan', 'asc')->get();
        
        $banners->transform(function ($item) {
            if ($item->gambar_banner) {
                $item->gambar_url = url('storage/' . $item->gambar_banner);
            }
            return $item;
        });

        return response()->json(['status' => 'success', 'data' => $banners], 200);
    }

    // 3. Detail 1 Banner
    public function show($id) {
        $banner = Banner::find($id);
        if (!$banner) return response()->json(['status' => 'error', 'message' => 'Banner tidak ditemukan'], 404);

        if ($banner->gambar_banner) {
            $banner->gambar_url = url('storage/' . $banner->gambar_banner);
        }

        return response()->json(['status' => 'success', 'data' => $banner], 200);
    }

    // 4. CREATE: Tambah Banner + Upload File
    public function store(Request $request) {
        $request->validate([
            'nama_banner' => 'required|string|max:150',
            // Wajib berupa file gambar!
            'gambar_banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', 
            'urutan' => 'nullable|integer',
            'shown' => 'nullable|boolean'
        ]);

        $data = $request->except('gambar_banner');

        // Trik Cerdas: Jika Admin tidak mengisi urutan, otomatis taruh di urutan paling belakang
        if (!isset($data['urutan'])) {
            $maxUrutan = Banner::max('urutan');
            $data['urutan'] = $maxUrutan ? $maxUrutan + 1 : 1;
        }

        // Simpan gambar fisik
        if ($request->hasFile('gambar_banner')) {
            $path = $request->file('gambar_banner')->store('banner_images', 'public');
            $data['gambar_banner'] = $path;
        }

        $banner = Banner::create($data);

        return response()->json(['status' => 'success', 'message' => 'Banner berhasil ditambahkan!', 'data' => $banner], 201);
    }

    // 5. UPDATE: Ubah data atau ganti gambar banner
    public function update(Request $request, $id) {
        $banner = Banner::find($id);
        if (!$banner) return response()->json(['status' => 'error', 'message' => 'Banner tidak ditemukan'], 404);

        $request->validate([
            'nama_banner' => 'sometimes|string|max:150',
            'gambar_banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'urutan' => 'sometimes|integer',
            'shown' => 'sometimes|boolean'
        ]);

        $data = $request->except('gambar_banner');

        // Jika ada gambar baru yang diupload
        if ($request->hasFile('gambar_banner')) {
            // Hapus gambar lama
            if ($banner->gambar_banner) {
                Storage::disk('public')->delete($banner->gambar_banner);
            }
            // Simpan gambar baru
            $path = $request->file('gambar_banner')->store('banner_images', 'public');
            $data['gambar_banner'] = $path;
        }

        $banner->update($data);

        return response()->json(['status' => 'success', 'message' => 'Banner berhasil diperbarui!', 'data' => $banner], 200);
    }

    // 6. DELETE: Hapus Banner dan Gambar fisiknya
    public function destroy($id) {
        $banner = Banner::find($id);
        if (!$banner) return response()->json(['status' => 'error', 'message' => 'Banner tidak ditemukan'], 404);

        if ($banner->gambar_banner) {
            Storage::disk('public')->delete($banner->gambar_banner);
        }

        $banner->delete();

        return response()->json(['status' => 'success', 'message' => 'Banner berhasil dihapus!'], 200);
    }
}