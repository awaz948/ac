<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function (): void {
    require __DIR__.'/offline.php';
});
