<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'device_id', 'video_id', 'offline_download_id',
        'event_type', 'payload', 'occurred_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'occurred_at' => 'datetime',
    ];
}
