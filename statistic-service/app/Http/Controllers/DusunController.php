<?php
namespace App\Http\Controllers;
use App\Models\Dusun;
use Illuminate\Http\Request;

class DusunController extends Controller
{
    // Ini keajaiban Laravel! Kita panggil Dusun beserta SEMUA relasi demografinya sekaligus
    public function index() {
        $dusuns = Dusun::with(['usias', 'pendidikans', 'pekerjaans', 'agamas', 'perkawinans'])->get();
        return response()->json(['status' => 'success', 'data' => $dusuns], 200);
    }

    public function store(Request $request) {
        $request->validate(['id' => 'required|string|max:5|unique:dusuns,id', 'nama_dusun' => 'required']);
        $dusun = Dusun::create($request->all());
        return response()->json(['status' => 'success', 'message' => 'Dusun berhasil ditambahkan!', 'data' => $dusun], 201);
    }
}