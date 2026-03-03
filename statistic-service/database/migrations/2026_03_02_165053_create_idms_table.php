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
    Schema::create('idms', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->integer('tahun_idm');
        $table->string('status_idm', 50); // Contoh: Tertinggal, Berkembang, Maju
        $table->decimal('score_idm', 5, 4)->default(0);
        $table->decimal('sosial_idm', 5, 4)->default(0);
        $table->decimal('ekonomi_idm', 5, 4)->default(0);
        $table->decimal('lingkungan_idm', 5, 4)->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idms');
    }
};
