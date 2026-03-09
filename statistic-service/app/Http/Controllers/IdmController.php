<?php

namespace App\Http\Controllers;

use App\Models\Idm;
use Illuminate\Http\Request;

class IdmController extends Controller
{
    // 1. READ: Menampilkan semua data IDM
    public function index() 
    {
        $data = Idm::orderBy('tahun_idm', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    // 2. READ: Menampilkan 1 detail data IDM
    public function show($id) 
    {
        $idm = Idm::find($id);
        if (!$idm) return response()->json(['status' => 'error', 'message' => 'Data IDM tidak ditemukan'], 404);
        
        return response()->json(['status' => 'success', 'data' => $idm], 200);
    }

    // 3. CREATE: Menyimpan data IDM baru
    public function store(Request $request) 
    {
        $request->validate([
            // Tahun tidak boleh ganda di database
            'tahun_idm' => 'required|integer|unique:idms,tahun_idm',
            // Validasi status resmi dari Kemendesa
            'status_idm' => 'required|string|in:Sangat Tertinggal,Tertinggal,Berkembang,Maju,Mandiri',
            'score_idm' => 'required|numeric|min:0|max:1',
            'sosial_idm' => 'required|numeric|min:0|max:1',
            'ekonomi_idm' => 'required|numeric|min:0|max:1',
            'lingkungan_idm' => 'required|numeric|min:0|max:1',
        ]);

        $idm = Idm::create($request->all());
        
        return response()->json([
            'status' => 'success', 
            'message' => 'Data IDM Tahun '.$idm->tahun_idm.' berhasil disimpan!',
            'data' => $idm
        ], 201);
    }

    // 4. UPDATE: Mengubah data IDM
    public function update(Request $request, $id) 
    {
        $idm = Idm::find($id);
        if (!$idm) return response()->json(['status' => 'error', 'message' => 'Data IDM tidak ditemukan'], 404);

        $request->validate([
            // Boleh mengubah tahun, asalkan tidak bentrok dengan tahun milik data lain
            'tahun_idm' => 'sometimes|integer|unique:idms,tahun_idm,' . $idm->id,
            'status_idm' => 'sometimes|string|in:Sangat Tertinggal,Tertinggal,Berkembang,Maju,Mandiri',
            'score_idm' => 'sometimes|numeric|min:0|max:1',
            'sosial_idm' => 'sometimes|numeric|min:0|max:1',
            'ekonomi_idm' => 'sometimes|numeric|min:0|max:1',
            'lingkungan_idm' => 'sometimes|numeric|min:0|max:1',
        ]);

        $idm->update($request->all());
        
        return response()->json([
            'status' => 'success', 
            'message' => 'Data IDM berhasil diperbarui!',
            'data' => $idm
        ], 200);
    }

    // 5. DELETE: Menghapus data IDM
    public function destroy($id) 
    {
        $idm = Idm::find($id);
        if (!$idm) return response()->json(['status' => 'error', 'message' => 'Data IDM tidak ditemukan'], 404);

        $tahun = $idm->tahun_idm;
        $idm->delete();
        
        return response()->json([
            'status' => 'success', 
            'message' => 'Data IDM Tahun '.$tahun.' berhasil dihapus!'
        ], 200);
    }
}