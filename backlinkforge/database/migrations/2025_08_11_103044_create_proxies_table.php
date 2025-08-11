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
        Schema::create('proxies', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->integer('port');
            $table->string('username')->nullable();
            $table->text('password_encrypted')->nullable(); // encrypted password
            $table->enum('type', ['http', 'https', 'socks4', 'socks5'])->default('http');
            $table->string('country', 2)->nullable(); // ISO country code
            $table->enum('status', ['active', 'inactive', 'testing', 'failed'])->default('active');
            $table->integer('success_count')->default(0);
            $table->integer('failure_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('last_tested_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['type', 'status']);
            $table->index(['country', 'status']);
            $table->index('status');
            $table->index('last_used_at');
            $table->unique(['ip', 'port']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proxies');
    }
};
