<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // <-- Tambahkan ini

class Apbdes extends Model
{
    use HasFactory, HasUuids; // <-- Tambahkan HasUuids di sini

    protected $guarded = []; // Mengizinkan semua kolom diisi
}
