<?php

namespace App\Services;

use App\Models\OfflineDownload;
use App\Models\OfflineLicenseSession;
use App\Models\Student;
use Carbon\CarbonImmutable;

class LicenseLifecycleService
{
    public function refreshOfflineLicense(OfflineDownload $download): array
    {
        $download->update(['expires_at' => CarbonImmutable::now()->addHours(24)]);

        OfflineLicenseSession::query()->create([
            'student_id' => $download->student_id,
            'device_id' => $download->device_id,
            'video_id' => $download->video_id,
            'offline_download_id' => $download->id,
            'license_type' => 'renewal',
            'license_provider' => 'drm_vendor',
            'issued_at' => now(),
            'expires_at' => $download->expires_at,
            'status' => 'active',
        ]);

        return ['expires_at' => $download->expires_at];
    }

    public function releaseOfflineLicense(OfflineDownload $download): void
    {
        $download->update(['download_status' => 'deleted']);

        OfflineLicenseSession::query()
            ->where('offline_download_id', $download->id)
            ->update(['status' => 'released', 'released_at' => now()]);
    }

    public function revokeOfflineLicense(OfflineDownload $download, string $reason): void
    {
        $download->update(['download_status' => 'revoked']);

        OfflineLicenseSession::query()
            ->where('offline_download_id', $download->id)
            ->update(['status' => 'revoked', 'last_error' => $reason]);
    }

    public function extendOfflineLicense(OfflineDownload $download, int $hours): array
    {
        $download->update(['expires_at' => CarbonImmutable::parse($download->expires_at)->addHours($hours)]);

        return ['expires_at' => $download->expires_at];
    }

    public function resetStudentOfflineDownloads(Student $student): void
    {
        OfflineDownload::query()->where('student_id', $student->id)->update(['download_status' => 'revoked']);
        OfflineLicenseSession::query()->where('student_id', $student->id)->update(['status' => 'revoked']);
    }
}
