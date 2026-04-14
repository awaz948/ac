<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnsureDeviceIntegrity
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $device = $request->attributes->get('student_device');

        if ($device && $device->is_compromised) {
            return response()->json(['message' => 'Device integrity check failed.'], 423);
        }

        return $next($request);
    }
}
