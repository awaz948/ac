<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineLicenseSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'device_id', 'video_id', 'offline_download_id', 'license_type',
        'license_provider', 'license_reference', 'issued_at', 'expires_at', 'renewed_at',
        'released_at', 'status', 'last_error',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'renewed_at' => 'datetime',
        'released_at' => 'datetime',
    ];
}
