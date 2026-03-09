<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Wajib ditambahkan

class Idm extends Model
{
    use HasFactory, HasUuids;

    // Mengizinkan semua kolom diisi
    protected $guarded = [];
}