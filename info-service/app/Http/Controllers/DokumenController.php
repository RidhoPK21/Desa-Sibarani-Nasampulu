<?php
namespace App\Http\Controllers;
use App\Models\Dokumen;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index() {
        return response()->json(['status' => 'success', 'data' => Dokumen::orderBy('tanggal_upload', 'desc')->get()], 200);
    }

    public function store(Request $request) {
        $request->validate(['nama_ppid' => 'required', 'jenis_ppid' => 'required', 'file' => 'required']);
        $dokumen = Dokumen::create($request->all());
        return response()->json(['status' => 'success', 'message' => 'Dokumen berhasil diunggah!', 'data' => $dokumen], 201);
    }
}