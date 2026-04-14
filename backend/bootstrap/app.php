<?php

use App\Http\Middleware\EnsureDeviceIntegrity;
use App\Http\Middleware\EnsureOfflineDownloadAllowed;
use App\Http\Middleware\EnsureOfflineLicenseValid;

return [
    'middleware_aliases' => [
        'offline.download.allowed' => EnsureOfflineDownloadAllowed::class,
        'offline.license.valid' => EnsureOfflineLicenseValid::class,
        'device.integrity' => EnsureDeviceIntegrity::class,
    ],
];
