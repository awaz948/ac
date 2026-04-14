<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offline_downloads', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('entitlement_id')->constrained('video_entitlements')->cascadeOnDelete();
            $table->enum('download_status', ['queued', 'downloading', 'paused', 'completed', 'failed', 'deleted', 'revoked', 'expired']);
            $table->string('local_asset_id')->nullable();
            $table->string('local_manifest_path')->nullable();
            $table->enum('drm_scheme', ['widevine', 'fairplay']);
            $table->text('key_set_id')->nullable();
            $table->unsignedBigInteger('downloaded_bytes')->default(0);
            $table->unsignedBigInteger('file_size')->nullable();
            $table->decimal('progress_percent', 5, 2)->default(0);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('last_opened_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offline_downloads');
    }
};
