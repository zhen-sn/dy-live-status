<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Streamer extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'douyin_url',
        'douyin_id',
        'is_monitoring',
        'is_live',
        'last_live_time',
        'last_check_time',
    ];

    protected $casts = [
        'is_monitoring' => 'boolean',
        'is_live' => 'boolean',
        'last_live_time' => 'datetime',
        'last_check_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function monitorLogs(): HasMany
    {
        return $this->hasMany(MonitorLog::class);
    }
}