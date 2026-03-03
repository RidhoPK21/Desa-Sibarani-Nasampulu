<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('apbdes', function (Blueprint $table) {
        $table->uuid('id')->primary(); // <-- Menggunakan UUID sesuai standar Anda
        $table->string('nama_desa', 100);
        $table->integer('tahun'); // <-- YEAR di SQL menjadi integer di Laravel

        // =========================
        // PENDAPATAN DESA
        // =========================
        $table->decimal('pendapatan_asli_desa', 15, 2)->default(0);
        $table->decimal('dana_desa', 15, 2)->default(0);
        $table->decimal('alokasi_dana_desa', 15, 2)->default(0);
        $table->decimal('bagi_hasil_pajak_retribusi', 15, 2)->default(0);
        $table->decimal('bantuan_keuangan_provinsi', 15, 2)->default(0);
        $table->decimal('bantuan_keuangan_kabupaten', 15, 2)->default(0);
        $table->decimal('lain_lain_pendapatan_sah', 15, 2)->default(0);

        // =========================
        // 1. BIDANG PENYELENGGARAAN PEMERINTAHAN DESA
        // =========================
        $table->decimal('siltap_kepala_desa', 15, 2)->default(0);
        $table->decimal('siltap_perangkat_desa', 15, 2)->default(0);
        $table->decimal('jaminan_sosial_perangkat', 15, 2)->default(0);
        $table->decimal('operasional_pemerintah_desa', 15, 2)->default(0);
        $table->decimal('tunjangan_bpd', 15, 2)->default(0);
        $table->decimal('operasional_bpd', 15, 2)->default(0);
        $table->decimal('operasional_dana_desa', 15, 2)->default(0);
        $table->decimal('sarana_prasarana_kantor', 15, 2)->default(0);
        $table->decimal('pengisian_mutasi_perangkat', 15, 2)->default(0);

        // =========================
        // 2. BIDANG PELAKSANAAN PEMBANGUNAN DESA
        // =========================
        $table->decimal('penyuluhan_pendidikan', 15, 2)->default(0);
        $table->decimal('sarana_pendidikan', 15, 2)->default(0);
        $table->decimal('sarana_perpustakaan', 15, 2)->default(0);
        $table->decimal('pengelolaan_perpustakaan', 15, 2)->default(0);
        $table->decimal('posyandu', 15, 2)->default(0);
        $table->decimal('penyuluhan_kesehatan', 15, 2)->default(0);
        $table->decimal('pemeliharaan_jalan_lingkungan', 15, 2)->default(0);
        $table->decimal('pembangunan_jalan_desa', 15, 2)->default(0);
        $table->decimal('pembangunan_jalan_usaha_tani', 15, 2)->default(0);
        $table->decimal('dokumen_tata_ruang', 15, 2)->default(0);
        $table->decimal('talud_irigasi', 15, 2)->default(0);
        $table->decimal('sanitasi_pemukiman', 15, 2)->default(0);
        $table->decimal('fasilitas_pengelolaan_sampah', 15, 2)->default(0);
        $table->decimal('jaringan_internet_desa', 15, 2)->default(0);

        // =========================
        // 3. BIDANG PEMBINAAN KEMASYARAKATAN
        // =========================
        $table->decimal('pembinaan_pkk', 15, 2)->default(0);

        // =========================
        // 4. BIDANG PEMBERDAYAAN MASYARAKAT
        // =========================
        $table->decimal('pelatihan_pertanian_peternakan', 15, 2)->default(0);
        $table->decimal('pelatihan_aparatur_desa', 15, 2)->default(0);
        $table->decimal('penyusunan_rencana_program', 15, 2)->default(0);
        $table->decimal('insentif_kader_pembangunan', 15, 2)->default(0);
        $table->decimal('insentif_kader_kesehatan_paud', 15, 2)->default(0);

        // =========================
        // 5. BIDANG PENANGGULANGAN BENCANA
        // =========================
        $table->decimal('penanggulangan_bencana', 15, 2)->default(0);
        $table->decimal('keadaan_mendesak', 15, 2)->default(0);

        // =========================
        // PEMBIAYAAN
        // =========================
        $table->decimal('silpa_tahun_sebelumnya', 15, 2)->default(0);
        $table->decimal('penyertaan_modal_desa', 15, 2)->default(0);

        $table->timestamps(); // <-- Ini otomatis membuat created_at dan updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apbdes');
    }
};
