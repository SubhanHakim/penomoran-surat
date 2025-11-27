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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('kode_klafifikasi')->nullable();
            $table->enum('daftar_pengirim', ['DPRD', 'Sekretariat DPRD'])->default('DPRD');
            $table->string('nomor_surat')->unique();
            $table->string('perihal')->nullable();
            $table->string('ditujukan_kepada')->nullable();
            $table->string('jenis_surat')->nullable();
            $table->tinyInteger('bulan')->default((int) now()->format('n'));
            $table->smallInteger('tahun')->default((int) now()->format('Y'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
