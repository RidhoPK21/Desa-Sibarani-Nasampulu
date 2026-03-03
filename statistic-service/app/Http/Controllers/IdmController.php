<?php
namespace App\Http\Controllers;
use App\Models\Idm;
use Illuminate\Http\Request;

class IdmController extends Controller
{
    public function index() {
        return response()->json(['status' => 'success', 'data' => Idm::orderBy('tahun_idm', 'desc')->get()], 200);
    }

    public function store(Request $request) {
        $request->validate(['tahun_idm' => 'required|integer|unique:idms,tahun_idm']);
        $idm = Idm::create($request->all());
        return response()->json(['status' => 'success', 'data' => $idm], 201);
    }
}