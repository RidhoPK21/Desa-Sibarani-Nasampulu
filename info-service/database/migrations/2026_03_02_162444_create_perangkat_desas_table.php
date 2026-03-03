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
    Schema::create('perangkat_desas', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('nama', 150);
        $table->string('jabatan', 100);
        $table->string('foto', 255)->nullable(); // Nullable jika foto belum ada
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perangkat_desas');
    }
};
