<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dusun extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    // Menambahkan Total Penduduk otomatis di JSON Response
    protected $appends = ['total_penduduk'];

    public function getTotalPendudukAttribute()
    {
        return $this->penduduk_laki + $this->penduduk_perempuan;
    }

    public function usias() { return $this->hasMany(DusunUsia::class, 'dusun_id'); }
    public function pendidikans() { return $this->hasMany(DusunPendidikan::class, 'dusun_id'); }
    public function pekerjaans() { return $this->hasMany(DusunPekerjaan::class, 'dusun_id'); }
    public function agamas() { return $this->hasMany(DusunAgama::class, 'dusun_id'); }
    public function perkawinans() { return $this->hasMany(DusunPerkawinan::class, 'dusun_id'); } 
}