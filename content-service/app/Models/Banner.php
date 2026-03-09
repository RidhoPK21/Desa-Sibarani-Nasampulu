<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 

class Banner extends Model
{
    use HasFactory, HasUuids; 

    protected $guarded = [];

    // Pastikan Laravel membaca 'shown' sebagai true/false (bukan 1/0)
    protected $casts = [
        'shown' => 'boolean',
        'urutan' => 'integer'
    ];
}