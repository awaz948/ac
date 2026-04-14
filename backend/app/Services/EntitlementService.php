<?php

namespace App\Services;

use App\Models\OfflineDownload;
use App\Models\VideoEntitlement;
use Carbon\CarbonImmutable;

class EntitlementService
{
    public function listOfflineEligibleVideos($student)
    {
        return VideoEntitlement::query()
            ->where('student_id', $student->id)
            ->where('can_download_offline', true)
            ->whereNull('revoked_at')
            ->with('video')
            ->get();
    }

    public function requestOfflineDownload($student, array $payload): OfflineDownload
    {
        $entitlement = VideoEntitlement::query()
            ->where('student_id', $student->id)
            ->where('video_id', $payload['video_id'])
            ->where('lesson_id', $payload['lesson_id'])
            ->whereNull('revoked_at')
            ->firstOrFail();

        abort_unless($entitlement->can_download_offline, 403, 'Offline download not allowed.');
        abort_if($entitlement->download_count >= $entitlement->max_download_count, 429, 'Download limit reached.');

        $download = OfflineDownload::query()->create([
            'student_id' => $student->id,
            'video_id' => $payload['video_id'],
            'lesson_id' => $payload['lesson_id'],
            'device_id' => $payload['device_id'],
            'entitlement_id' => $entitlement->id,
            'download_status' => 'queued',
            'drm_scheme' => 'widevine',
            'downloaded_bytes' => 0,
            'progress_percent' => 0,
            'expires_at' => CarbonImmutable::now()->addHours($student->activeSubscription?->offline_license_duration_hours ?? 168),
        ]);

        $entitlement->increment('download_count');

        return $download;
    }
}
