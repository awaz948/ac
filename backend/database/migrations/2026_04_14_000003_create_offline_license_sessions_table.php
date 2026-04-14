<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offline_license_sessions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offline_download_id')->constrained('offline_downloads')->cascadeOnDelete();
            $table->enum('license_type', ['streaming', 'persistent_offline', 'renewal', 'release']);
            $table->string('license_provider');
            $table->string('license_reference')->nullable();
            $table->dateTime('issued_at');
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('renewed_at')->nullable();
            $table->dateTime('released_at')->nullable();
            $table->enum('status', ['issued', 'active', 'expired', 'revoked', 'released', 'failed'])->default('issued');
            $table->text('last_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offline_license_sessions');
    }
};
