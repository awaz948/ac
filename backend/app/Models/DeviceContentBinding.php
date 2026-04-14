<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceContentBinding extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'device_id', 'video_id', 'offline_download_id', 'binding_status',
        'bound_at', 'unbound_at', 'reason',
    ];

    protected $casts = [
        'bound_at' => 'datetime',
        'unbound_at' => 'datetime',
    ];
}
