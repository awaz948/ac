<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('video_entitlements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('can_stream')->default(true);
            $table->boolean('can_download_offline')->default(false);
            $table->unsignedInteger('max_offline_devices')->default(1);
            $table->unsignedInteger('max_download_count')->default(1);
            $table->unsignedInteger('download_count')->default(0);
            $table->dateTime('license_expires_at')->nullable();
            $table->dateTime('offline_expires_at')->nullable();
            $table->dateTime('revoked_at')->nullable();
            $table->text('revoked_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_entitlements');
    }
};
