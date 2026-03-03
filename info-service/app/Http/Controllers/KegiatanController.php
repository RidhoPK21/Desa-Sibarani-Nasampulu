<?php
namespace App\Http\Controllers;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index() {
        return response()->json(['status' => 'success', 'data' => Kegiatan::orderBy('tanggal_pelaksana', 'asc')->get()], 200);
    }

    public function store(Request $request) {
        $request->validate(['judul_kegiatan' => 'required', 'jenis_kegiatan' => 'required', 'tanggal_pelaksana' => 'required|date']);
        $kegiatan = Kegiatan::create($request->all());
        return response()->json(['status' => 'success', 'message' => 'Kegiatan berhasil ditambahkan!', 'data' => $kegiatan], 201);
    }
}