<?php

namespace App\Http\Controllers;

use App\Models\Apbdes;
use Illuminate\Http\Request;

class ApbdesController extends Controller
{
    // 1. READ: Menampilkan data APBDes yang SEDANG AKTIF SAJA
    public function index()
    {
        // Hapus filter 'is_aktif', lalu urutkan berdasarkan tahun dan versi terbesar
        $data = Apbdes::orderBy('tahun', 'desc')
                      ->orderBy('versi', 'desc')
                      ->get(); 

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    // 2. FITUR BARU: Menampilkan semua riwayat APBDes di tahun tertentu
    public function riwayat($tahun)
    {
        // Ambil semua versi di tahun tersebut, urutkan dari versi terbesar
        $data = Apbdes::where('tahun', $tahun)
                      ->orderBy('versi', 'desc')
                      ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    // 3. READ: Menampilkan 1 detail data
    public function show($id)
    {
        $apbdes = Apbdes::find($id);
        if (!$apbdes) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);

        return response()->json(['status' => 'success', 'data' => $apbdes], 200);
    }

    // 4. CREATE: Menyimpan data APBDes AWAL (Versi 1)
    // 4. CREATE: Menyimpan data APBDes AWAL (Versi 1)
    public function store(Request $request)
    {
        $request->validate([
            'nama_desa' => 'required|string|max:100',
            'tahun' => 'required|integer',
        ]);

        // =======================================================
        // 🔥 FITUR BARU: CEK DUPLIKASI TAHUN
        // =======================================================
        $cekTahun = Apbdes::where('tahun', $request->tahun)->first();
        if ($cekTahun) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data APBDes untuk tahun ' . $request->tahun . ' sudah ada! Silakan gunakan tombol "Ubah & Buat Versi Baru" (ikon pesan) pada tabel.'
            ], 400); // Tolak permintaan (Bad Request)
        }
        // =======================================================

        $data = $request->all();
        $data['versi'] = 1; // Paksa jadi versi 1
        $data['is_aktif'] = true; 

        // Ubah null jadi 0
        foreach ($data as $key => $value) {
            if ($value === null || $value === '') $data[$key] = 0;
        }

        $apbdes = Apbdes::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data APBDes Tahun ' . $apbdes->tahun . ' berhasil disimpan!',
            'data' => $apbdes
        ], 201);
    }

    // 5. UPDATE: Membuat APBDes Perubahan (Versi Baru)
    public function update(Request $request, $id)
    {
        $apbdesLama = Apbdes::find($id);
        
        if (!$apbdesLama) {
            return response()->json(['status' => 'error', 'message' => 'Data APBDes tidak ditemukan'], 404);
        }

        $request->validate([
            'nama_desa' => 'sometimes|string|max:100',
            'tahun' => 'sometimes|integer',
            'alasan_perubahan' => 'required|string' // Wajib diisi jika mau mengubah!
        ]);

        $dataBaru = $request->all();

        // Antisipasi data null
        foreach ($dataBaru as $key => $value) {
            if ($value === null || $value === '') $dataBaru[$key] = 0;
        }

        // --- LOGIKA ENTERPRISE (VERSIONING) --- //
        
        // a. Matikan versi lama
        $apbdesLama->update(['is_aktif' => false]);

        // b. Atur parameter untuk versi baru
        $dataBaru['versi'] = $apbdesLama->versi + 1; // Naikkan versinya (misal dari 1 jadi 2)
        $dataBaru['is_aktif'] = true;
        // Pastikan tahun dan nama desa tidak berubah jika tidak dikirim ulang
        $dataBaru['tahun'] = $request->tahun ?? $apbdesLama->tahun;
        $dataBaru['nama_desa'] = $request->nama_desa ?? $apbdesLama->nama_desa;

        // c. Simpan sebagai baris data baru di database
        $apbdesBaru = Apbdes::create($dataBaru);

        return response()->json([
            'status' => 'success',
            'message' => 'APBDes Perubahan (Versi '.$apbdesBaru->versi.') berhasil diterbitkan!',
            'data' => $apbdesBaru
        ], 200);
    }

    // 6. DELETE: Menghapus data
    public function destroy($id)
    {
        $apbdes = Apbdes::find($id);
        if (!$apbdes) return response()->json(['status' => 'error', 'message' => 'Data APBDes tidak ditemukan'], 404);

        $tahun = $apbdes->tahun;
        $apbdes->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data APBDes berhasil dihapus!'
        ], 200);
    }
}