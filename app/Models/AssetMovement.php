<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AssetMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'from_department_id',
        'to_department_id',
        'assigned_from',
        'assigned_to',
        'movement_type',
        'movement_date',
        'notes',
    ];

    protected $casts = [
        'movement_date' => 'date',
    ];

    public const TYPE_CHECKOUT = 'checkout';
    public const TYPE_CHECKIN = 'checkin';
    public const TYPE_TRANSFER = 'transfer';
    public const TYPE_ASSIGNMENT = 'assignment';

    public const TYPES = [
        self::TYPE_CHECKOUT,
        self::TYPE_CHECKIN,
        self::TYPE_TRANSFER,
        self::TYPE_ASSIGNMENT,
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function fromDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'from_department_id');
    }

    public function toDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    public function assignedFromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_from');
    }

    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getMovementIconAttribute(): string
    {
        return match($this->movement_type) {
            self::TYPE_CHECKOUT => 'box-arrow-right',
            self::TYPE_CHECKIN => 'box-arrow-in-left',
            self::TYPE_TRANSFER => 'arrow-left-right',
            self::TYPE_ASSIGNMENT => 'person-plus',
            default => 'arrow-left-right',
        };
    }

    public function getMovementColorAttribute(): string
    {
        return match($this->movement_type) {
            self::TYPE_CHECKOUT => 'text-primary',
            self::TYPE_CHECKIN => 'text-success',
            self::TYPE_TRANSFER => 'text-info',
            self::TYPE_ASSIGNMENT => 'text-warning',
            default => 'text-secondary',
        };
    }
}
