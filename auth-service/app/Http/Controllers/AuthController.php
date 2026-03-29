<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username' => 'nullable|string',
            'email'    => 'nullable|email',
            'password' => 'required|string'
        ]);

        // 2. Cari User (Cek username jika ada, jika tidak cek email)
        $user = User::when($request->username, function ($query, $username) {
                        return $query->where('username', $username);
                    })
                    ->when($request->email, function ($query, $email) {
                        return $query->orWhere('email', $email);
                    })
                    ->first();

        // 3. Verifikasi Password dan keberadaan User
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kredensial yang Anda masukkan salah. Silakan coba lagi.'
            ], 401);
        }

        // 4. Buat Token (Sanctum)
        $token = $user->createToken('token_desa')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil!',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
            ],
            'token' => $token
        ], 200);
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil!'
        ], 200);
    }
}
