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
        Schema::create('pembinaan_pendampingan_sections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->foreignId('frekuensi_id')->nullable()->constrained('frekuensi_pendampingan_options');
            $table->boolean('pembinaan_spiritual');
            $table->boolean('pembinaan_usaha');
            $table->boolean('pendampingan_rutin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembinaan_pendampingan_sections');
    }
};
