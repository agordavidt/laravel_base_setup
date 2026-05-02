<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_alerts', function (Blueprint $table) {
            $table->id();

            // What triggered the alert
            $table->string('alert_type', 100)->index();

            // The offending IP
            $table->string('ip_address', 45)->index();

            // How many events triggered this, and over what window
            $table->unsignedInteger('event_count');
            $table->unsignedInteger('window_minutes');

            // Additional context (e.g. attempted email, path)
            $table->json('context')->nullable();

            // When the threshold was breached
            $table->timestamp('triggered_at')->useCurrent()->index();

            // Acknowledgement — nullable means unreviewed
            // Setting acknowledged_at is the ONLY permitted update to this record
            $table->timestamp('acknowledged_at')->nullable()->index();

            // Which super admin reviewed it (FK soft — null if user deleted)
            $table->unsignedBigInteger('acknowledged_by')->nullable();
            $table->foreign('acknowledged_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();

            // No updated_at — state is tracked through acknowledged_at alone
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_alerts');
    }
};