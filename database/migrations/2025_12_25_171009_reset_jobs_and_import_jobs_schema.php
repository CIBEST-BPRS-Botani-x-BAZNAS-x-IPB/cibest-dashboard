<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
            Schema::table('import_jobs', function (Blueprint $table) {
                $table->dropForeign(['job_id']);
            });

            Schema::table('import_jobs', function (Blueprint $table) {
                $table->dropColumn('job_id');
            });

            Schema::table('import_jobs', function (Blueprint $table) {
                $table->foreignId('job_id')
                      ->nullable()
                      ->after('id');
            });

            Schema::table('import_jobs', function (Blueprint $table) {
                $table->foreign('job_id')
                      ->references('id')
                      ->on('jobs')
                      ->nullOnDelete();
            });
    }

    public function down(): void
    {
        // Tidak disediakan karena ini destructive migration
    }
};