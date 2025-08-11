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
        Schema::create('campaign_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('node_type'); // web2.0, wiki, forum, profile, bookmark, rss, pdf
            $table->string('name');
            $table->json('position')->nullable(); // x, y coordinates
            $table->json('config')->nullable(); // node-specific configuration
            $table->enum('status', ['pending', 'active', 'completed', 'failed'])->default('pending');
            $table->integer('order_index')->default(0);
            $table->timestamps();

            // Indexes for performance
            $table->index(['campaign_id', 'node_type']);
            $table->index(['campaign_id', 'status']);
            $table->index('order_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_nodes');
    }
};
