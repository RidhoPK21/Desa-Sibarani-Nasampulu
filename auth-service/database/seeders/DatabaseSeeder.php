<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil file AdminSeeder khusus yang sudah Anda buat
        $this->call([
            AdminSeeder::class,
        ]);
        
        $this->command->info('Seeding selesai: Admin Desa Sibarani berhasil ditambahkan!');
    }
}