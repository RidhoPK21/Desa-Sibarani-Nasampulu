<?php

namespace App\Http\Controllers;

use App\Models\Apbdes;
use Illuminate\Http\Request;

class ApbdesController extends Controller
{
    // 1. Menampilkan semua data APBDes (Read)
    public function index()
    {
        // Mengambil data dan mengurutkannya dari tahun terbaru
        $data = Apbdes::orderBy('tahun', 'desc')->get(); 

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    // 2. Menyimpan data APBDes baru (Create)
    public function store(Request $request)
    {
        // Validasi data wajib
        $request->validate([
            'nama_desa' => 'required|string|max:100',
            'tahun' => 'required|integer',
        ]);

        // Simpan semua data yang dikirim dari form/Postman
        $apbdes = Apbdes::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data APBDes Tahun ' . $apbdes->tahun . ' berhasil disimpan!',
            'data' => $apbdes
        ], 201);
    }
}