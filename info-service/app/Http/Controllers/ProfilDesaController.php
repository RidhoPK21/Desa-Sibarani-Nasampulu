<?php
namespace App\Http\Controllers;
use App\Models\KataSambutan;
use App\Models\VisiMisi;
use App\Models\PerangkatDesa;
use Illuminate\Http\Request;

class ProfilDesaController extends Controller
{
    // --- KATA SAMBUTAN ---
    public function getKataSambutan() {
        return response()->json(['status' => 'success', 'data' => KataSambutan::first()]);
    }
    public function storeKataSambutan(Request $request) {
        KataSambutan::truncate(); // Hapus yang lama, ganti yang baru (karena cuma butuh 1)
        $data = KataSambutan::create($request->all());
        return response()->json(['status' => 'success', 'data' => $data], 201);
    }

    // --- VISI MISI ---
    public function getVisiMisi() {
        return response()->json(['status' => 'success', 'data' => VisiMisi::first()]);
    }
    public function storeVisiMisi(Request $request) {
        VisiMisi::truncate(); // Hapus yang lama, ganti yang baru
        $data = VisiMisi::create($request->all());
        return response()->json(['status' => 'success', 'data' => $data], 201);
    }

    // --- PERANGKAT DESA ---
    public function getPerangkatDesa() {
        return response()->json(['status' => 'success', 'data' => PerangkatDesa::all()]);
    }
    public function storePerangkatDesa(Request $request) {
        $data = PerangkatDesa::create($request->all());
        return response()->json(['status' => 'success', 'data' => $data], 201);
    }
}