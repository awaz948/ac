<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OfflineDownload;
use App\Models\Student;
use App\Services\LicenseLifecycleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminOfflineController extends Controller
{
    public function __construct(private readonly LicenseLifecycleService $licenseLifecycleService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => OfflineDownload::query()->with(['student', 'video', 'device'])->latest()->paginate(),
        ]);
    }

    public function studentDownloads(Student $student): JsonResponse
    {
        return response()->json([
            'data' => OfflineDownload::query()->where('student_id', $student->id)->latest()->paginate(),
        ]);
    }

    public function revoke(OfflineDownload $download): JsonResponse
    {
        $this->licenseLifecycleService->revokeOfflineLicense($download, 'admin_revoke');

        return response()->json(['message' => 'Download revoked.']);
    }

    public function extendLicense(Request $request, OfflineDownload $download): JsonResponse
    {
        $data = $request->validate(['hours' => ['required', 'integer', 'min:1', 'max:720']]);
        $result = $this->licenseLifecycleService->extendOfflineLicense($download, $data['hours']);

        return response()->json(['data' => $result]);
    }

    public function resetStudentDownloads(Student $student): JsonResponse
    {
        $this->licenseLifecycleService->resetStudentOfflineDownloads($student);

        return response()->json(['message' => 'Student offline downloads reset.']);
    }
}
