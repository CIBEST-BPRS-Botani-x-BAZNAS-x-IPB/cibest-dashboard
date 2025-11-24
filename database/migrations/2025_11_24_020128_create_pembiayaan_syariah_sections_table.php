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
        Schema::create('pembiayaan_syariah_sections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->date('bulan_tahun_menerima');
            $table->text('lembaga_keuangan_syariah');
            $table->foreignId('jangka_waktu_option_id')->constrained()->cascadeOnDelete();
            $table->integer('frekuensi_penerimaan');
            $table->bigInteger('total_nilai_pembiayaan');
            $table->string('lembaga_syariah_lain')->nullable();
            $table->string('lembaga_konvensional')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembiayaan_syariah_sections');
    }
};
