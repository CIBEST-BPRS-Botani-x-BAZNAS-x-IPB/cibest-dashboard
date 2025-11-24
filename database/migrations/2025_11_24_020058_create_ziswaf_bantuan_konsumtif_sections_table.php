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
        Schema::create('ziswaf_bantuan_konsumtif_sections', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pangan')->default(0);
            $table->bigInteger('kesehatan')->default(0);
            $table->bigInteger('pendidikan')->default(0);
            $table->bigInteger('lainnya')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ziswaf_bantuan_konsumtif_sections');
    }
};
