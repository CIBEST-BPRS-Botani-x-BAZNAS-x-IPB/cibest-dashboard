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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('admin_verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('admin_verified_at')->nullable();
            $table->unsignedBigInteger('admin_verified_by')->nullable();
            $table->foreign('admin_verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['admin_verified_by']);
            $table->dropColumn(['admin_verification_status', 'admin_verified_at', 'admin_verified_by']);
        });
    }
};
