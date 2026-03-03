<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Desa Sibarani',
            'username' => 'admin_sibarani',
            'email' => 'admin@sibaraninasampulu.desa.id',
            'password' => Hash::make('password_desa_2026'), // Enkripsi kata sandi menggunakan Bcrypt
        ]);
    }
}