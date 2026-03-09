<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Wajib untuk manajemen file

class DokumenController extends Controller
{
    // 1. READ: Menampilkan semua dokumen
    public function index()
    {
        $dokumen = Dokumen::orderBy('tanggal_upload', 'desc')->get();
        
        // Memodifikasi path file menjadi URL lengkap agar bisa didownload Frontend
        $dokumen->transform(function ($item) {
            if ($item->file) {
                $item->file_url = url('storage/' . $item->file);
            }
            return $item;
        });

        return response()->json(['status' => 'success', 'data' => $dokumen], 200);
    }

    // 2. READ: Menampilkan 1 dokumen spesifik
    public function show($id)
    {
        $dokumen = Dokumen::find($id);
        
        if (!$dokumen) {
            return response()->json(['status' => 'error', 'message' => 'Dokumen tidak ditemukan'], 404);
        }

        if ($dokumen->file) {
            $dokumen->file_url = url('storage/' . $dokumen->file);
        }
        
        return response()->json(['status' => 'success', 'data' => $dokumen], 200);
    }

    // 3. CREATE: Menyimpan dokumen baru beserta file aslinya
    public function store(Request $request)
    {
        $request->validate([
            'nama_ppid' => 'required|string|max:255',
            // Validasi ketat sesuai Enum di database
            'jenis_ppid' => 'required|in:Regulasi,Laporan Keuangan,SK Kades,Lainnya',
            'deskripsi_ppid' => 'nullable|string',
            // Validasi file: Harus berupa file dokumen, maksimal 5MB (5120 KB)
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:5120' 
        ]);

        $data = $request->except('file'); // Ambil semua teks, pisahkan filenya

        // Logika Menyimpan File fisik ke folder storage/app/public/dokumen_ppid
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('dokumen_ppid', 'public');
            $data['file'] = $path; // Simpan path-nya ke database (contoh: dokumen_ppid/namafile.pdf)
        }

        $dokumen = Dokumen::create($data);

        return response()->json([
            'status' => 'success', 
            'message' => 'Dokumen berhasil diunggah!', 
            'data' => $dokumen
        ], 201);
    }

    // 4. UPDATE: Mengubah data dokumen (dan mengganti file jika ada yang baru)
    public function update(Request $request, $id)
    {
        $dokumen = Dokumen::find($id);
        
        if (!$dokumen) {
            return response()->json(['status' => 'error', 'message' => 'Dokumen tidak ditemukan'], 404);
        }

        $request->validate([
            'nama_ppid' => 'sometimes|string|max:255',
            'jenis_ppid' => 'sometimes|in:Regulasi,Laporan Keuangan,SK Kades,Lainnya',
            'deskripsi_ppid' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120'
        ]);

        $data = $request->except('file');

        // Jika Admin mengunggah file baru saat Update
        if ($request->hasFile('file')) {
            // Hapus file lama dari hard drive agar penyimpanan tidak penuh
            if ($dokumen->file) {
                Storage::disk('public')->delete($dokumen->file);
            }
            // Simpan file baru
            $path = $request->file('file')->store('dokumen_ppid', 'public');
            $data['file'] = $path;
        }

        $dokumen->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Dokumen berhasil diperbarui!',
            'data' => $dokumen
        ], 200);
    }

    // 5. DELETE: Menghapus data dari database sekaligus file fisiknya
    public function destroy($id)
    {
        $dokumen = Dokumen::find($id);
        
        if (!$dokumen) {
            return response()->json(['status' => 'error', 'message' => 'Dokumen tidak ditemukan'], 404);
        }

        // Hapus file fisik dari folder storage sebelum menghapus datanya
        if ($dokumen->file) {
            Storage::disk('public')->delete($dokumen->file);
        }

        $dokumen->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Dokumen berhasil dihapus!'
        ], 200);
    }
}