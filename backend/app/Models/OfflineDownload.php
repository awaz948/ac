<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'video_id', 'lesson_id', 'device_id', 'entitlement_id',
        'download_status', 'local_asset_id', 'local_manifest_path', 'drm_scheme',
        'key_set_id', 'downloaded_bytes', 'file_size', 'progress_percent',
        'started_at', 'completed_at', 'last_opened_at', 'expires_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_opened_at' => 'datetime',
        'expires_at' => 'datetime',
        'progress_percent' => 'float',
    ];

    public function student() { return $this->belongsTo(Student::class); }
    public function video() { return $this->belongsTo(Video::class); }
    public function device() { return $this->belongsTo(Device::class); }
}
