<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gunakan updateOrCreate untuk memastikan data admin selalu ada dan diperbarui
        User::updateOrCreate(
            ['username' => 'admin_sibarani'],
            [
                'name' => 'Admin Desa Sibarani',
                'email' => 'admin@sibaraninasampulu.desa.id',
                'password' => Hash::make('password_desa_2026'), // Eksplisit melakukan hashing
            ]
        );
    }
}
