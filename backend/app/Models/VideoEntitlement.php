<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoEntitlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'video_id',
        'lesson_id',
        'device_id',
        'can_stream',
        'can_download_offline',
        'max_offline_devices',
        'max_download_count',
        'download_count',
        'license_expires_at',
        'offline_expires_at',
        'revoked_at',
        'revoked_reason',
    ];

    protected $casts = [
        'can_stream' => 'boolean',
        'can_download_offline' => 'boolean',
        'license_expires_at' => 'datetime',
        'offline_expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
