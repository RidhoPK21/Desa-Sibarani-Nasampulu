<?php

namespace App\Http\Controllers;

use App\Models\Apbdes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApbdesController extends Controller
{
    // 1. READ: Menampilkan data APBDes yang SEDANG AKTIF SAJA
    public function index()
    {
        // Urutkan berdasarkan tahun dan versi terbesar
        $data = Apbdes::orderBy('tahun', 'desc')
                      ->orderBy('versi', 'desc')
                      ->get();

        $data->transform(function ($item) {
            if ($item->file) {
                $item->file_url = url('storage/' . $item->file);
            }
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    // 2. FITUR RIWAYAT: Menampilkan semua versi di tahun tertentu
    public function riwayat($tahun)
    {
        $data = Apbdes::where('tahun', $tahun)
                      ->orderBy('versi', 'desc')
                      ->get();

        $data->transform(function ($item) {
            if ($item->file) {
                $item->file_url = url('storage/' . $item->file);
            }
            return $item;
        });

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

        if ($apbdes->file) {
            $apbdes->file_url = url('storage/' . $apbdes->file);
        }

        return response()->json(['status' => 'success', 'data' => $apbdes], 200);
    }

    // 4. CREATE: Menyimpan data APBDes AWAL (Versi 1)
    public function store(Request $request)
    {
        $request->validate([
            'nama_desa' => 'required|string|max:100',
            'tahun' => 'required|integer',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120'
        ]);

        $cekTahun = Apbdes::where('tahun', $request->tahun)->first();
        if ($cekTahun) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data APBDes untuk tahun ' . $request->tahun . ' sudah ada!'
            ], 400);
        }

        $data = $request->except('file');
        $data['versi'] = 1;
        $data['is_aktif'] = true;

        // Ubah null jadi 0 untuk field numerik
        foreach ($data as $key => $value) {
            if ($value === null || $value === '') {
                if (!in_array($key, ['nama_desa', 'alasan_perubahan', 'id'])) {
                    $data[$key] = 0;
                }
            }
        }

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('dokumen_apbdes', 'public');
            $data['file'] = $path;
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
            'alasan_perubahan' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120'
        ]);

        $dataBaru = $request->except('file');

        foreach ($dataBaru as $key => $value) {
            if ($value === null || $value === '') {
                if (!in_array($key, ['nama_desa', 'alasan_perubahan', 'id'])) {
                    $dataBaru[$key] = 0;
                }
            }
        }

        // a. Matikan versi lama
        $apbdesLama->update(['is_aktif' => false]);

        // b. Atur parameter untuk versi baru
        $dataBaru['versi'] = $apbdesLama->versi + 1;
        $dataBaru['is_aktif'] = true;
        $dataBaru['tahun'] = $request->tahun ?? $apbdesLama->tahun;
        $dataBaru['nama_desa'] = $request->nama_desa ?? $apbdesLama->nama_desa;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('dokumen_apbdes', 'public');
            $dataBaru['file'] = $path;
        } else {
            // Opsional: Jika file tidak diupdate, copy file dari versi lama?
            // Biasanya tiap versi punya dokumen sendiri, tapi jika tidak diupload baru kita biarkan null atau copy.
            $dataBaru['file'] = $apbdesLama->file;
        }

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

        if ($apbdes->file) {
            Storage::disk('public')->delete($apbdes->file);
        }

        $apbdes->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data APBDes berhasil dihapus!'
        ], 200);
    }
}
