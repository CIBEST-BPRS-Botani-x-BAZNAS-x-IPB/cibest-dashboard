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
        Schema::create('pendapatan_ketenagakerjaan_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cibest_form_id')->constrained();

            $table->string('nama_anggota');
            $table->foreignId('status_pekerjaan_option_id')->constrained();
            $table->foreignId('jenis_pekerjaan_option_id')->constrained();
            $table->bigInteger('rata_rata_pendapatan')->default(0);
            $table->bigInteger('pendapatan_tidak_tetap')->default(0);
            $table->bigInteger('pendapatan_aset')->default(0);
            $table->bigInteger('total_pendapatan')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendapatan_ketenagakerjaan_sections');
    }
};
