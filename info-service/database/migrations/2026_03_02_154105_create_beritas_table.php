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
    Schema::create('beritas', function (Blueprint $table) {
        $table->uuid('id')->primary(); // Standar UUID kita
        $table->string('judul');
        $table->string('slug')->unique(); // Untuk URL ramah SEO, cth: /berita/rapat-desa-2026
        $table->enum('kategori', ['Berita', 'Pengumuman']); // Membedakan jenis pos
        $table->longText('konten'); // Isi lengkap berita/pengumuman
        $table->string('penulis')->nullable(); // Nama admin yang memposting
        $table->string('gambar_url')->nullable(); // Link/path gambar jika ada (opsional)
        $table->boolean('is_published')->default(true); // Status apakah langsung tayang atau draft
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
