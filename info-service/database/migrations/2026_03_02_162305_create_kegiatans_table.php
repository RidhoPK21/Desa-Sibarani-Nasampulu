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
    Schema::create('kegiatans', function (Blueprint $table) {
        $table->uuid('id')->primary(); // Pengganti id_kegiatan
        
        // Enum bisa Anda sesuaikan isinya sesuai kebutuhan desa
        $table->enum('jenis_kegiatan', ['kegiatan kerja', 'program kerja', 'bantuan sosial']);        
        $table->string('judul_kegiatan', 255);
        $table->text('deskripsi_kegiatan')->nullable();
        $table->string('gambar', 255)->nullable(); // URL/Path gambar poster kegiatan
        
        $table->date('tanggal_pelaksana');
        $table->date('tanggal_berakhir')->nullable();
        
        // Enum status kegiatan
        $table->enum('status_kegiatan', ['Akan Datang', 'Berlangsung', 'Selesai', 'Batal'])->default('Akan Datang');
        
        $table->timestamps(); // Ini otomatis membuat kolom created_at (dan updated_at)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};
