<?php

use App\Http\Controllers\Api\AdminOfflineController;
use App\Http\Controllers\Api\StudentOfflineController;
use Illuminate\Support\Facades\Route;

Route::prefix('student/offline')
    ->middleware([
        'auth:sanctum',
        'student.active',
        'student.subscription.active',
        'device.registered',
        'offline.download.allowed',
        'device.integrity',
    ])
    ->group(function (): void {
        Route::get('/available', [StudentOfflineController::class, 'available']);
        Route::post('/request-download', [StudentOfflineController::class, 'requestDownload']);
        Route::post('/{download}/start', [StudentOfflineController::class, 'start']);
        Route::post('/{download}/progress', [StudentOfflineController::class, 'progress']);
        Route::post('/{download}/complete', [StudentOfflineController::class, 'complete']);
        Route::post('/{download}/fail', [StudentOfflineController::class, 'fail']);
        Route::get('/my-downloads', [StudentOfflineController::class, 'myDownloads']);
        Route::delete('/my-downloads/{download}', [StudentOfflineController::class, 'destroy']);
        Route::post('/{download}/refresh-license', [StudentOfflineController::class, 'refreshLicense'])
            ->middleware('offline.license.valid');
        Route::post('/{download}/release-license', [StudentOfflineController::class, 'releaseLicense']);
        Route::post('/sync', [StudentOfflineController::class, 'sync']);
    });

Route::prefix('admin')
    ->middleware(['auth:sanctum'])
    ->group(function (): void {
        Route::get('/offline-downloads', [AdminOfflineController::class, 'index']);
        Route::get('/students/{student}/offline-downloads', [AdminOfflineController::class, 'studentDownloads']);
        Route::post('/offline-downloads/{download}/revoke', [AdminOfflineController::class, 'revoke']);
        Route::post('/offline-downloads/{download}/extend-license', [AdminOfflineController::class, 'extendLicense']);
        Route::post('/students/{student}/offline-downloads/reset', [AdminOfflineController::class, 'resetStudentDownloads']);
    });
