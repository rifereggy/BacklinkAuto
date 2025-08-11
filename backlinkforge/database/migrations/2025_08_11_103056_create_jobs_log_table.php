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
        Schema::create('jobs_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('node_id')->nullable()->constrained('campaign_nodes')->onDelete('cascade');
            $table->string('job_type'); // create_account, post_content, validate_link, etc.
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'retrying'])->default('pending');
            $table->json('payload')->nullable(); // job input data
            $table->json('result')->nullable(); // job output data
            $table->text('error_message')->nullable();
            $table->integer('attempts')->default(0);
            $table->integer('max_attempts')->default(3);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['campaign_id', 'status']);
            $table->index(['node_id', 'status']);
            $table->index(['job_type', 'status']);
            $table->index('status');
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs_log');
    }
};
