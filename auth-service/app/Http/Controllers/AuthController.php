<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. LOGIN: Mencetak Token
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username atau password salah!'
            ], 401);
        }

        // Buat Token
        $token = $user->createToken('token_desa')->plainTextToken;

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

    // 2. CEK PROFIL: Mengambil data Admin yang sedang login
    public function me(Request $request)
    {
        // Mengambil user berdasarkan Token yang dikirim di Header
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ], 200);
    }

    // 3. LOGOUT: Menghancurkan Token
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan ini dari database
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil, Token telah dihancurkan!'
        ], 200);
    }
}