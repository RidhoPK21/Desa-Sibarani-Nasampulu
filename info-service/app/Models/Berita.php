<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // <-- Tambahkan ini

class Berita extends Model
{
    use HasFactory, HasUuids; // <-- Tambahkan HasUuids di sini

    // Mengizinkan semua kolom diisi secara massal
    protected $guarded = []; 
}