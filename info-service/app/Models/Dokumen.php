<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class Dokumen extends Model
{
    use HasFactory, HasUuids;

    // Menggunakan Fillable agar lebih aman dan seragam dengan model lain
    protected $fillable = [
        'nama_ppid',
        'jenis_ppid',
        'deskripsi_ppid',
        'file',
        // 'tanggal_upload' tidak perlu dimasukkan karena sudah otomatis dari database (useCurrent)
    ];
}