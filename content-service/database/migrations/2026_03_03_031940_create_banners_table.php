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
    Schema::create('banners', function (Blueprint $table) {
        $table->uuid('id')->primary(); // Pengganti id_banner
        $table->string('nama_banner', 150);
        $table->string('gambar_banner', 255); // URL atau path lokasi gambar disimpan
        $table->integer('urutan')->default(0); // Untuk mengatur urutan slide gambar
        $table->boolean('shown')->default(true); // Status apakah banner ditampilkan atau disembunyikan
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
