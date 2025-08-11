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
        Schema::create('captcha_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->nullable()->constrained('jobs_log')->onDelete('cascade');
            $table->string('method'); // tesseract, 2captcha, anticaptcha, etc.
            $table->text('image_data')->nullable(); // base64 encoded image
            $table->string('image_url')->nullable(); // URL to captcha image
            $table->text('result')->nullable(); // solved captcha text
            $table->decimal('confidence', 5, 2)->nullable(); // confidence score 0-100
            $table->enum('status', ['pending', 'processing', 'solved', 'failed'])->default('pending');
            $table->json('metadata')->nullable(); // additional captcha data
            $table->timestamp('solved_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['job_id', 'status']);
            $table->index(['method', 'status']);
            $table->index('status');
            $table->index('solved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('captcha_requests');
    }
};
