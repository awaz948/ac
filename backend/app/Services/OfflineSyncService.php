<?php

namespace App\Services;

use App\Models\OfflineDownload;
use App\Models\OfflineEvent;

class OfflineSyncService
{
    public function markStart(OfflineDownload $download): void
    {
        $download->update(['download_status' => 'downloading', 'started_at' => now()]);
        $this->event($download, 'download_started');
    }

    public function markProgress(OfflineDownload $download, array $data): void
    {
        $download->update($data + ['download_status' => 'downloading']);
    }

    public function markComplete(OfflineDownload $download, array $data): void
    {
        $download->update($data + ['download_status' => 'completed', 'completed_at' => now()]);
        $this->event($download, 'download_completed');
    }

    public function markFailure(OfflineDownload $download, ?string $error = null): void
    {
        $download->update(['download_status' => 'failed']);
        $this->event($download, 'download_failed', ['error' => $error]);
    }

    public function sync($student, array $payload): array
    {
        $downloads = OfflineDownload::query()->where('student_id', $student->id)->get();

        return [
            'keep_active' => $downloads->where('download_status', 'completed')->pluck('id')->values(),
            'revoke' => $downloads->whereIn('download_status', ['revoked', 'expired'])->pluck('id')->values(),
            'renew' => $downloads->where('download_status', 'completed')->take(5)->pluck('id')->values(),
            'echo' => $payload,
        ];
    }

    private function event(OfflineDownload $download, string $eventType, ?array $payload = null): void
    {
        OfflineEvent::query()->create([
            'student_id' => $download->student_id,
            'device_id' => $download->device_id,
            'video_id' => $download->video_id,
            'offline_download_id' => $download->id,
            'event_type' => $eventType,
            'payload' => $payload,
            'occurred_at' => now(),
        ]);
    }
}
