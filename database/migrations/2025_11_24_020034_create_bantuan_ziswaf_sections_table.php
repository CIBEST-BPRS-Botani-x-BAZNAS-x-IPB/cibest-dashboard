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
        Schema::create('bantuan_ziswaf_sections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->date('bulan_tahun_menerima');
            $table->integer('frekuensi_penerimaan');
            $table->bigInteger('total_nilai_bantuan');
            $table->foreignId('ziswaf_bantuan_konsumtif_section_id')->unique()->constrained();
            $table->foreignId('ziswaf_bantuan_produktif_section_id')->unique()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuan_ziswaf_sections');
    }
};
