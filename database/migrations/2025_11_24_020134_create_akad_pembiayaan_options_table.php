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
        Schema::create('akad_pembiayaan_options', function (Blueprint $table) {
            $table->id();
            $table->string('value')->unique();
            $table->boolean('is_other')->default(true);
        });

        Schema::create('akad_pembiayaan_option_pembiayaan_syariah_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembiayaan_syariah_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('akad_pembiayaan_option_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akad_pembiayaan_options');
    }
};
