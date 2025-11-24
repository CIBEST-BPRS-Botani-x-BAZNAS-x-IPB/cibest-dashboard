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
        Schema::create('karakteristik_rumah_tangga_sections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('cibest_form_id')->constrained();
            $table->string('nama_anggota');
            $table->string('hubungan_kepala_keluarga');
            $table->integer('usia');
            $table->foreignId('jenis_kelamin_option_id')->constrained();
            $table->foreignId('status_perkawinan_option_id')->constrained();
            $table->foreignId('pendidikan_formal_option_id')->constrained();
            $table->foreignId('pendidikan_non_formal_option_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karakteristik_rumah_tangga_sections');
    }
};
