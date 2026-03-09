<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kegiatan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'jenis_kegiatan',
        'judul_kegiatan',
        'deskripsi_kegiatan',
        'gambar',
        'tanggal_pelaksana',
        'tanggal_berakhir',
        'status_kegiatan',
    ];
}