<?php

use App\Enums\FormType;
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
        Schema::create('import_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('jobs'); // To track the queue job ID
            $table->enum('type', FormType::cases());
            $table->string('filename');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('errors')->nullable(); // JSON string of errors if any
            $table->integer('total_rows')->nullable();
            $table->integer('processed_rows')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_jobs');
    }
};
