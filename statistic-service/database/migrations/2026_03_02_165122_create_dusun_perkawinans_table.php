<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // TYPO DIPERBAIKI: dusun_perkawinans
        Schema::create('dusun_perkawinans', function (Blueprint $table) {
            $table->id();
            $table->string('dusun_id', 5);
            $table->string('status_perkawinan', 50);
            $table->integer('jumlah_jiwa')->default(0);
            $table->timestamps();
            $table->foreign('dusun_id')->references('id')->on('dusuns')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dusun_perkawinans');
    }
};