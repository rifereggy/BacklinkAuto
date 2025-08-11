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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->string('provider'); // wordpress, blogger, medium, etc.
            $table->string('username');
            $table->string('email');
            $table->text('password_encrypted'); // encrypted password
            $table->foreignId('proxy_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'created', 'active', 'suspended', 'failed'])->default('pending');
            $table->json('metadata')->nullable(); // additional account data
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['campaign_id', 'provider']);
            $table->index(['campaign_id', 'status']);
            $table->index(['provider', 'status']);
            $table->index('proxy_id');
            $table->unique(['provider', 'username', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
