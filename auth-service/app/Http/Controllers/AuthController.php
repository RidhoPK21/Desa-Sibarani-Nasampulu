<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input dari Frontend
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // 2. Cari admin berdasarkan username
        $user = User::where('username', $request->username)->first();

        // 3. Cek apakah user ada dan password cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username atau password salah!'
            ], 401);
        }

        // 4. Buatkan Token Akses (Sanctum)
        $token = $user->createToken('token_desa')->plainTextToken;

        // 5. Kembalikan respons sukses beserta tokennya
        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil!',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
            ],
            'token' => $token
        ], 200);
    }
}