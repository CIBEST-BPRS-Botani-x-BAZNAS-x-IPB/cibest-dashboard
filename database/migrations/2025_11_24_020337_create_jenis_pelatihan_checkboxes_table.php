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
        Schema::create('jenis_pelatihan_checkboxes', function (Blueprint $table) {
            $table->id();
            $table->string('value')->unique();
            $table->boolean('is_other')->default(true);
        });

        Schema::create('jenis_pelatihan_checkbox_pembinaan_pendampingan_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembinaan_pendampingan_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jenis_pelatihan_checkbox_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('pelatihan_sangat_membantu_checkbox_pembinaan_pendampingan_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembinaan_pendampingan_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jenis_pelatihan_checkbox_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pelatihan_checkboxes');
    }
};
