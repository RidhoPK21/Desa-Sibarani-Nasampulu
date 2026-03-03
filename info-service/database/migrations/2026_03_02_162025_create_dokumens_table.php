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
    Schema::create('dokumens', function (Blueprint $table) {
        $table->uuid('id')->primary(); // Pengganti id_ppid
        $table->string('nama_ppid', 255);
        $table->enum('jenis_ppid', ['Regulasi', 'Laporan Keuangan', 'SK Kades', 'Lainnya']); // Sesuaikan enum jika ada opsi khusus
        $table->text('deskripsi_ppid')->nullable();
        $table->timestamp('tanggal_upload')->useCurrent();
        $table->string('file', 255); // Untuk menyimpan path file/PDF
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
