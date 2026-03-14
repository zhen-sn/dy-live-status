<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitorLog extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'streamer_id',
        'was_live',
        'is_live',
        'notification_sent',
        'response_data',
    ];

    protected $casts = [
        'was_live' => 'boolean',
        'is_live' => 'boolean',
        'notification_sent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function streamer(): BelongsTo
    {
        return $this->belongsTo(Streamer::class);
    }
}