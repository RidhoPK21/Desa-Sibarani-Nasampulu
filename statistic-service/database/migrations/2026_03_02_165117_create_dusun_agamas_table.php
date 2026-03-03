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
        Schema::create('dusun_agamas', function (Blueprint $table) {
        $table->id();
        $table->string('dusun_id', 5);
        $table->string('agama', 50);
        $table->integer('jumlah_jiwa')->default(0);
        $table->timestamps();
        $table->foreign('dusun_id')->references('id')->on('dusuns')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dusun_agamas');
    }
};
