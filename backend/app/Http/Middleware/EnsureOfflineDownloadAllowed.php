<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnsureOfflineDownloadAllowed
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $subscription = $request->user()?->activeSubscription;

        if (! $subscription || ! $subscription->allow_offline) {
            return response()->json(['message' => 'Offline download is not allowed for this subscription.'], 403);
        }

        return $next($request);
    }
}
