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
        Schema::create('lembaga_ziswaf_checkboxes', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->boolean('is_other')->default(true);
        });

        Schema::create('bantuan_ziswaf_section_lembaga_ziswaf_checkbox', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bantuan_ziswaf_section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lembaga_ziswaf_checkbox_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembaga_ziswaf_checkboxes');
    }
};
