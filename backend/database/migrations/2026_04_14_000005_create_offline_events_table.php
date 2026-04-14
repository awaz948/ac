<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offline_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offline_download_id')->nullable()->constrained('offline_downloads')->nullOnDelete();
            $table->enum('event_type', [
                'download_requested', 'download_started', 'download_completed', 'download_failed',
                'license_issued', 'license_refreshed', 'license_revoked', 'license_expired',
                'offline_play_started', 'offline_play_finished', 'offline_asset_deleted', 'tamper_detected',
            ]);
            $table->json('payload')->nullable();
            $table->dateTime('occurred_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offline_events');
    }
};
