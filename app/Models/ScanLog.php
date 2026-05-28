<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScanLog extends Model
{
    protected $fillable = [
        'asset_id',
        'scanned_by',
        'scan_type',
        'device',
        'ip_address',
        'location',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public function getScanTypeIconAttribute(): string
    {
        return match($this->scan_type) {
            'qr_code'       => 'fas fa-qrcode',
            'serial_number' => 'fas fa-hashtag',
            default         => 'fas fa-qrcode',
        };
    }

    public function getScanTypeBadgeAttribute(): string
    {
        return match($this->scan_type) {
            'qr_code'       => 'bg-info',
            'serial_number' => 'bg-warning text-dark',
            default         => 'bg-info',
        };
    }
}
