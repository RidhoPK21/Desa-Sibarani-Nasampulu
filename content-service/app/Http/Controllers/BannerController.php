<?php
namespace App\Http\Controllers;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    // Menampilkan semua banner yang status shown = true, diurutkan sesuai urutan
    public function index() {
        $banners = Banner::where('shown', true)->orderBy('urutan', 'asc')->get();
        return response()->json(['status' => 'success', 'data' => $banners], 200);
    }

    // Menambahkan banner baru
    public function store(Request $request) {
        $request->validate([
            'nama_banner' => 'required|max:150',
            'gambar_banner' => 'required'
        ]);

        $banner = Banner::create($request->all());
        return response()->json(['status' => 'success', 'message' => 'Banner berhasil ditambahkan!', 'data' => $banner], 201);
    }
}