<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- Wajib ditambah
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // <-- Wajib ditambah
class VisiMisi extends Model
{
    use HasFactory, HasUuids; 

    protected $guarded = [];
}
