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
    Schema::create('dusuns', function (Blueprint $table) {
        $table->string('id', 5)->primary(); // Contoh isi: 'D01', 'D02'
        $table->string('nama_dusun', 100);
        $table->integer('penduduk_laki')->default(0);
        $table->integer('penduduk_perempuan')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dusuns');
    }
};
