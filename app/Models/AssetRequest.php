<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetRequest extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ISSUED = 'issued';
    const STATUS_CANCELLED = 'cancelled';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    protected $fillable = [
        'user_id',
        'asset_name',
        'quantity',
        'reason',
        'priority',
        'status',
        'notes',
        'remarks',
        'approved_by',
        'approved_at',
        'issued_by',
        'issued_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'approved_at' => 'datetime',
        'issued_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeIssued($query)
    {
        return $query->where('status', self::STATUS_ISSUED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_APPROVED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_ISSUED => 'bg-info',
            self::STATUS_CANCELLED => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => 'bg-success',
            self::PRIORITY_MEDIUM => 'bg-warning text-dark',
            self::PRIORITY_HIGH => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getStatusDisplayNameAttribute(): string
    {
        return ucfirst($this->status);
    }

    public function getPriorityDisplayNameAttribute(): string
    {
        return ucfirst($this->priority);
    }
}
