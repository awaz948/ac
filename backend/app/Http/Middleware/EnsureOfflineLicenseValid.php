<?php

namespace App\Http\Middleware;

use App\Models\OfflineDownload;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnsureOfflineLicenseValid
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $download = $request->route('download');

        if ($download instanceof OfflineDownload && in_array($download->download_status, ['revoked', 'expired'], true)) {
            return response()->json(['message' => 'Offline license is revoked or expired.'], 403);
        }

        return $next($request);
    }
}
