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
        Schema::create('pembiayaan_lain_checkboxes', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->boolean('is_other')->default(true);
        });

        Schema::create('bantuan_ziswaf_section_pembiayaan_lain_checkbox', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bantuan_ziswaf_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pembiayaan_lain_checkbox_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('pembiayaan_lain_checkbox_pembiayaan_syariah_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembiayaan_syariah_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pembiayaan_lain_checkbox_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembiayaan_lain_checkboxes');
    }
};
