<?php

namespace App\Http\Controllers;

use App\Models\Dusun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DusunController extends Controller
{
    // 1. READ: Tampilkan semua dusun + relasi
    public function index() {
        $dusuns = Dusun::with(['usias', 'pendidikans', 'pekerjaans', 'agamas', 'perkawinans'])->get();
        return response()->json(['status' => 'success', 'data' => $dusuns], 200);
    }

    // 2. READ: Detail 1 Dusun + relasi
    public function show($id) {
        $dusun = Dusun::with(['usias', 'pendidikans', 'pekerjaans', 'agamas', 'perkawinans'])->find($id);
        if (!$dusun) return response()->json(['status' => 'error', 'message' => 'Dusun tidak ditemukan'], 404);
        return response()->json(['status' => 'success', 'data' => $dusun], 200);
    }

    // 3. CREATE: Simpan Dusun (Bisa langsung menyertakan array statistik)
    public function store(Request $request) {
        $request->validate([
            'id' => 'required|string|max:5|unique:dusuns,id', 
            'nama_dusun' => 'required|string|max:100',
            'penduduk_laki' => 'nullable|integer',
            'penduduk_perempuan' => 'nullable|integer',
        ]);

        // Menggunakan DB Transaction agar aman. Jika gagal satu, batal semua.
        DB::beginTransaction();
        try {
            $dusun = Dusun::create($request->only(['id', 'nama_dusun', 'penduduk_laki', 'penduduk_perempuan']));

            // Jika Frontend mengirimkan array data statistik langsung, simpan sekaligus!
            if ($request->has('usias')) $dusun->usias()->createMany($request->usias);
            if ($request->has('pendidikans')) $dusun->pendidikans()->createMany($request->pendidikans);
            if ($request->has('pekerjaans')) $dusun->pekerjaans()->createMany($request->pekerjaans);
            if ($request->has('agamas')) $dusun->agamas()->createMany($request->agamas);
            if ($request->has('perkawinans')) $dusun->perkawinans()->createMany($request->perkawinans);

            DB::commit();

            return response()->json([
                'status' => 'success', 
                'message' => 'Dusun dan Statistik berhasil ditambahkan!', 
                'data' => $dusun->load(['usias', 'pendidikans', 'pekerjaans', 'agamas', 'perkawinans']) // Load ulang untuk respons
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // 4. UPDATE: Ubah data Dusun dan/atau perbarui array statistiknya
    public function update(Request $request, $id) {
        $dusun = Dusun::find($id);
        if (!$dusun) return response()->json(['status' => 'error', 'message' => 'Dusun tidak ditemukan'], 404);

        $request->validate([
            'nama_dusun' => 'sometimes|string|max:100',
            'penduduk_laki' => 'sometimes|integer',
            'penduduk_perempuan' => 'sometimes|integer',
        ]);

        DB::beginTransaction();
        try {
            $dusun->update($request->only(['nama_dusun', 'penduduk_laki', 'penduduk_perempuan']));

            // Konsep "Sync": Hapus data statistik lama, timpa dengan array yang baru dikirim dari React
            if ($request->has('usias')) {
                $dusun->usias()->delete(); 
                $dusun->usias()->createMany($request->usias);
            }
            if ($request->has('pendidikans')) {
                $dusun->pendidikans()->delete(); 
                $dusun->pendidikans()->createMany($request->pendidikans);
            }
            if ($request->has('pekerjaans')) {
                $dusun->pekerjaans()->delete(); 
                $dusun->pekerjaans()->createMany($request->pekerjaans);
            }
            if ($request->has('agamas')) {
                $dusun->agamas()->delete(); 
                $dusun->agamas()->createMany($request->agamas);
            }
            if ($request->has('perkawinans')) {
                $dusun->perkawinans()->delete(); 
                $dusun->perkawinans()->createMany($request->perkawinans);
            }

            DB::commit();

            return response()->json([
                'status' => 'success', 
                'message' => 'Dusun dan Statistik berhasil diperbarui!', 
                'data' => $dusun->load(['usias', 'pendidikans', 'pekerjaans', 'agamas', 'perkawinans'])
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // 5. DELETE: Hapus dusun (Semua relasi otomatis terhapus karena aturan onDelete('cascade') di database)
    public function destroy($id) {
        $dusun = Dusun::find($id);
        if (!$dusun) return response()->json(['status' => 'error', 'message' => 'Dusun tidak ditemukan'], 404);

        $dusun->delete();
        return response()->json(['status' => 'success', 'message' => 'Dusun berhasil dihapus!'], 200);
    }
}