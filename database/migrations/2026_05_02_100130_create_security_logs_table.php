<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Main table — recent high-value security events
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event', 100)->index();
            $table->string('level', 20)->default('info');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_email', 255)->nullable();
            $table->string('ip_address', 45)->index();   // supports IPv6
            $table->string('user_agent', 500)->nullable();
            $table->string('path', 500)->nullable();
            $table->string('method', 10)->nullable();
            $table->json('context')->nullable();
            // No updated_at — this table is append-only by design
            $table->timestamp('created_at')->useCurrent()->index();
        });

        // Archive table — identical structure, holds records older than 90 days
        Schema::create('security_logs_archive', function (Blueprint $table) {
            $table->id();
            $table->string('event', 100)->index();
            $table->string('level', 20)->default('info');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_email', 255)->nullable();
            $table->string('ip_address', 45)->index();
            $table->string('user_agent', 500)->nullable();
            $table->string('path', 500)->nullable();
            $table->string('method', 10)->nullable();
            $table->json('context')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
            $table->timestamp('archived_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_logs_archive');
        Schema::dropIfExists('security_logs');
    }
};