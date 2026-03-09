<?php

namespace App\Http\Controllers;

use App\Models\KataSambutan;
use App\Models\VisiMisi;
use App\Models\PerangkatDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilDesaController extends Controller
{
    // ==========================================
    // --- KATA SAMBUTAN (CRUD LENGKAP) ---
    // ==========================================
    
    public function indexKataSambutan() {
        $data = KataSambutan::orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function showKataSambutan($id) {
        $data = KataSambutan::find($id);
        if (!$data) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function storeKataSambutan(Request $request) {
        $request->validate(['kata' => 'required|string']);
        $data = KataSambutan::create($request->all());
        return response()->json(['status' => 'success', 'message' => 'Kata Sambutan ditambahkan!', 'data' => $data], 201);
    }

    public function updateKataSambutan(Request $request, $id) {
        $data = KataSambutan::find($id);
        if (!$data) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        
        $request->validate(['kata' => 'sometimes|string']);
        $data->update($request->all());
        
        return response()->json(['status' => 'success', 'message' => 'Kata Sambutan diperbarui!', 'data' => $data], 200);
    }

    public function destroyKataSambutan($id) {
        $data = KataSambutan::find($id);
        if (!$data) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        
        $data->delete();
        return response()->json(['status' => 'success', 'message' => 'Kata Sambutan dihapus!'], 200);
    }

    // ==========================================
    // --- VISI MISI (CRUD LENGKAP) ---
    // ==========================================
    
    public function indexVisiMisi() {
        $data = VisiMisi::orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function showVisiMisi($id) {
        $data = VisiMisi::find($id);
        if (!$data) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function storeVisiMisi(Request $request) {
        $request->validate([
            'visi' => 'required|string',
            'misi' => 'required|string'
        ]);
        $data = VisiMisi::create($request->all());
        return response()->json(['status' => 'success', 'message' => 'Visi Misi ditambahkan!', 'data' => $data], 201);
    }

    public function updateVisiMisi(Request $request, $id) {
        $data = VisiMisi::find($id);
        if (!$data) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        
        $request->validate([
            'visi' => 'sometimes|string',
            'misi' => 'sometimes|string'
        ]);
        $data->update($request->all());
        
        return response()->json(['status' => 'success', 'message' => 'Visi Misi diperbarui!', 'data' => $data], 200);
    }

    public function destroyVisiMisi($id) {
        $data = VisiMisi::find($id);
        if (!$data) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        
        $data->delete();
        return response()->json(['status' => 'success', 'message' => 'Visi Misi dihapus!'], 200);
    }

    // ==========================================
    // --- PERANGKAT DESA (CRUD LENGKAP) ---
    // ==========================================
    
    public function indexPerangkatDesa() {
        $perangkat = PerangkatDesa::all();
        $perangkat->transform(function ($item) {
            if ($item->foto) {
                $item->foto_url = url('storage/' . $item->foto);
            }
            return $item;
        });
        return response()->json(['status' => 'success', 'data' => $perangkat], 200);
    }

    public function showPerangkatDesa($id) {
        $perangkat = PerangkatDesa::find($id);
        if (!$perangkat) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        
        if ($perangkat->foto) {
            $perangkat->foto_url = url('storage/' . $perangkat->foto);
        }
        return response()->json(['status' => 'success', 'data' => $perangkat], 200);
    }

    public function storePerangkatDesa(Request $request) {
        $request->validate([
            'nama' => 'required|string|max:150',
            'jabatan' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('perangkat_desa_images', 'public');
            $data['foto'] = $path;
        }

        $perangkat = PerangkatDesa::create($data);
        return response()->json(['status' => 'success', 'message' => 'Perangkat Desa ditambahkan!', 'data' => $perangkat], 201);
    }

    public function updatePerangkatDesa(Request $request, $id) {
        $perangkat = PerangkatDesa::find($id);
        if (!$perangkat) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);

        $request->validate([
            'nama' => 'sometimes|string|max:150',
            'jabatan' => 'sometimes|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($perangkat->foto) {
                Storage::disk('public')->delete($perangkat->foto);
            }
            $path = $request->file('foto')->store('perangkat_desa_images', 'public');
            $data['foto'] = $path;
        }

        $perangkat->update($data);
        return response()->json(['status' => 'success', 'message' => 'Data berhasil diperbarui!', 'data' => $perangkat], 200);
    }

    public function destroyPerangkatDesa($id) {
        $perangkat = PerangkatDesa::find($id);
        if (!$perangkat) return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);

        if ($perangkat->foto) {
            Storage::disk('public')->delete($perangkat->foto);
        }

        $perangkat->delete();
        return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus!'], 200);
    }
}