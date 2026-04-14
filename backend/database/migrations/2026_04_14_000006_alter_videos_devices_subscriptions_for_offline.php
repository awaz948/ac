<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table): void {
            $table->enum('drm_scheme', ['widevine', 'fairplay', 'multi'])->nullable()->after('drm_enabled');
            $table->string('content_id')->nullable()->after('drm_scheme');
            $table->string('license_policy_id')->nullable()->after('content_id');
            $table->boolean('allow_offline')->default(false)->after('license_policy_id');
            $table->unsignedInteger('offline_duration_hours')->nullable()->after('allow_offline');
            $table->unsignedInteger('max_offline_devices')->default(1)->after('offline_duration_hours');
            $table->unsignedInteger('max_download_count')->default(1)->after('max_offline_devices');
        });

        Schema::table('devices', function (Blueprint $table): void {
            $table->string('drm_device_id')->nullable()->after('device_uuid');
            $table->string('security_level')->nullable()->after('drm_device_id');
            $table->boolean('is_compromised')->default(false)->after('security_level');
            $table->dateTime('last_attestation_at')->nullable()->after('is_compromised');
        });

        Schema::table('subscriptions', function (Blueprint $table): void {
            $table->boolean('allow_offline')->default(true)->after('status');
            $table->unsignedInteger('offline_max_videos')->default(10)->after('allow_offline');
            $table->unsignedInteger('offline_license_duration_hours')->nullable()->after('offline_max_videos');
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table): void {
            $table->dropColumn([
                'drm_scheme', 'content_id', 'license_policy_id', 'allow_offline',
                'offline_duration_hours', 'max_offline_devices', 'max_download_count',
            ]);
        });

        Schema::table('devices', function (Blueprint $table): void {
            $table->dropColumn(['drm_device_id', 'security_level', 'is_compromised', 'last_attestation_at']);
        });

        Schema::table('subscriptions', function (Blueprint $table): void {
            $table->dropColumn(['allow_offline', 'offline_max_videos', 'offline_license_duration_hours']);
        });
    }
};
