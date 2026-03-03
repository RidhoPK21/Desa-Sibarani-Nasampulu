<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dusun extends Model
{
    use HasFactory;

    // Karena ID Dusun kita pakai string ('D01'), kita matikan auto-increment
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    // Relasi ke tabel-tabel demografi
    public function usias() { return $this->hasMany(DusunUsia::class, 'dusun_id'); }
    public function pendidikans() { return $this->hasMany(DusunPendidikan::class, 'dusun_id'); }
    public function pekerjaans() { return $this->hasMany(DusunPekerjaan::class, 'dusun_id'); }
    public function agamas() { return $this->hasMany(DusunAgama::class, 'dusun_id'); }
    public function perkawinans() { return $this->hasMany(DusunPerkawinan::class, 'dusun_id'); } // Sesuaikan dengan nama model Anda
}