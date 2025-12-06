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
        Schema::table('poverty_standards', function (Blueprint $table) {
            $table->dropColumn(['index_kesejahteraan_cibest', 'besaran_nilai_cibest_model']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poverty_standards', function (Blueprint $table) {
            $table->double('index_kesejahteraan_cibest')->nullable();
            $table->double('besaran_nilai_cibest_model')->nullable();
        });
    }
};
