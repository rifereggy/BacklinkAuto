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
        Schema::create('content_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->text('raw_spintax'); // original spintax content
            $table->text('generated_text'); // processed content
            $table->string('content_type')->default('article'); // article, comment, profile, etc.
            $table->boolean('ai_used')->default(false);
            $table->string('ai_provider')->nullable(); // openai, claude, etc.
            $table->integer('tokens')->nullable(); // token count for AI
            $table->json('metadata')->nullable(); // additional content data
            $table->enum('status', ['draft', 'ready', 'used', 'archived'])->default('draft');
            $table->timestamps();

            // Indexes for performance
            $table->index(['campaign_id', 'content_type']);
            $table->index(['campaign_id', 'status']);
            $table->index(['ai_used', 'status']);
            $table->index('content_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_items');
    }
};
