<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Apbdes extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    // Beritahu Laravel untuk selalu menyertakan kolom virtual ini saat API dipanggil
    protected $appends = ['total_pendapatan', 'total_belanja'];

    // 1. Menghitung Total Pendapatan (Disesuaikan dengan kolom terbaru)
    public function getTotalPendapatanAttribute()
    {
        return $this->pendapatan_asli_desa + 
               $this->dana_desa + 
               $this->alokasi_dana_desa + 
               $this->bagi_hasil_pajak_retribusi + 
               $this->lain_lain_pendapatan_sah;
    }

    // 2. Menghitung Total Belanja (Disesuaikan dengan nama kolom terbaru di Bidang 1 & 2)
    public function getTotalBelanjaAttribute()
    {
        return (
            // Bidang 1: Pemerintahan
            $this->siltap_kepala_desa + $this->siltap_perangkat_desa + $this->jaminan_sosial_aparatur + 
            $this->operasional_pemerintahan_desa + $this->tunjangan_bpd + $this->operasional_bpd + 
            $this->operasional_dana_desa + $this->sarana_prasarana_kantor + $this->pengisian_mutasi_perangkat +
            
            // Bidang 2: Pembangunan
            $this->penyuluhan_pendidikan + $this->sarana_prasarana_pendidikan + $this->sarana_prasarana_perpustakaan + 
            $this->pengelolaan_perpustakaan + $this->penyelenggaraan_posyandu + $this->penyuluhan_kesehatan + 
            $this->pemeliharaan_jalan_lingkungan + $this->pembangunan_jalan_desa + 
            $this->pembangunan_jalan_usaha_tani + $this->dokumen_tata_ruang + $this->talud_irigasi + 
            $this->sanitasi_pemukiman + $this->fasilitas_pengelolaan_sampah + $this->jaringan_internet_desa +
            
            // Bidang 3: Pembinaan
            $this->pembinaan_pkk +
            
            // Bidang 4: Pemberdayaan
            $this->pelatihan_pertanian_peternakan + $this->pelatihan_aparatur_desa + 
            $this->penyusunan_rencana_program + $this->insentif_kader_pembangunan + 
            $this->insentif_kader_kesehatan_paud +
            
            // Bidang 5: Penanggulangan Bencana
            $this->penanggulangan_bencana + $this->keadaan_mendesak
        );
    }
}