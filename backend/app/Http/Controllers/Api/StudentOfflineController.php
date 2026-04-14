<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OfflineDownload;
use App\Services\EntitlementService;
use App\Services\LicenseLifecycleService;
use App\Services\OfflineSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentOfflineController extends Controller
{
    public function __construct(
        private readonly EntitlementService $entitlementService,
        private readonly LicenseLifecycleService $licenseLifecycleService,
        private readonly OfflineSyncService $offlineSyncService,
    ) {
    }

    public function available(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->entitlementService->listOfflineEligibleVideos($request->user()),
        ]);
    }

    public function requestDownload(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'video_id' => ['required', 'integer'],
            'lesson_id' => ['required', 'integer'],
            'device_id' => ['required', 'integer'],
        ]);

        $download = $this->entitlementService->requestOfflineDownload($request->user(), $payload);

        return response()->json(['data' => $download], 201);
    }

    public function start(OfflineDownload $download): JsonResponse
    {
        $this->offlineSyncService->markStart($download);
        return response()->json(['message' => 'Download started.']);
    }

    public function progress(Request $request, OfflineDownload $download): JsonResponse
    {
        $data = $request->validate([
            'downloaded_bytes' => ['required', 'integer', 'min:0'],
            'file_size' => ['nullable', 'integer', 'min:0'],
            'progress_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $this->offlineSyncService->markProgress($download, $data);

        return response()->json(['message' => 'Progress updated.']);
    }

    public function complete(Request $request, OfflineDownload $download): JsonResponse
    {
        $data = $request->validate([
            'local_asset_id' => ['required', 'string', 'max:255'],
            'local_manifest_path' => ['nullable', 'string', 'max:1000'],
            'key_set_id' => ['nullable', 'string'],
        ]);

        $this->offlineSyncService->markComplete($download, $data);

        return response()->json(['message' => 'Download completed.']);
    }

    public function fail(Request $request, OfflineDownload $download): JsonResponse
    {
        $this->offlineSyncService->markFailure($download, $request->input('error'));

        return response()->json(['message' => 'Download marked as failed.']);
    }

    public function myDownloads(Request $request): JsonResponse
    {
        return response()->json([
            'data' => OfflineDownload::query()
                ->where('student_id', $request->user()->id)
                ->latest()
                ->paginate(),
        ]);
    }

    public function destroy(OfflineDownload $download): JsonResponse
    {
        $this->licenseLifecycleService->releaseOfflineLicense($download);

        return response()->json(['message' => 'Offline download removed.']);
    }

    public function refreshLicense(OfflineDownload $download): JsonResponse
    {
        $result = $this->licenseLifecycleService->refreshOfflineLicense($download);

        return response()->json(['data' => $result]);
    }

    public function releaseLicense(OfflineDownload $download): JsonResponse
    {
        $this->licenseLifecycleService->releaseOfflineLicense($download);

        return response()->json(['message' => 'License released.']);
    }

    public function sync(Request $request): JsonResponse
    {
        $result = $this->offlineSyncService->sync($request->user(), $request->all());

        return response()->json(['data' => $result]);
    }
}
