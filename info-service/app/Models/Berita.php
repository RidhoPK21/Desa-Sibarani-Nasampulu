<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // Wajib untuk UUID
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory, HasUuids; // Aktifkan UUID di sini

    // Kolom apa saja yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'gambar_url',
        'is_published',
    ];
}