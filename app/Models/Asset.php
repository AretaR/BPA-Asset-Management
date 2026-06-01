<?php

namespace App\Models;

use App\Services\QRCodeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'asset_tag',
        'serial_number',
        'qr_uuid',
        'qr_code',
        'category_id',
        'department_id',
        'assigned_to',
        'purchase_date',
        'purchase_cost',
        'status',
        'location',
        'description',
        'image',
        'warranty_expiry',
        'manufacturer',
        'model',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'purchase_cost' => 'decimal:2',
    ];

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_RETIRED = 'retired';

    public const STATUSES = [
        self::STATUS_AVAILABLE,
        self::STATUS_ASSIGNED,
        self::STATUS_MAINTENANCE,
        self::STATUS_RETIRED,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($asset) {
            if (empty($asset->asset_tag)) {
                $asset->asset_tag = self::generateAssetTag();
            }
            if (empty($asset->qr_uuid)) {
                $asset->qr_uuid = Str::uuid()->toString();
            }
        });

        static::created(function ($asset) {
            $qrService = new QRCodeService();

            if (empty($asset->qr_code) && !empty($asset->qr_uuid)) {
                $publicUrl = $qrService->publicUrl($asset->qr_uuid);
                $asset->timestamps = false;
                $asset->updateQuietly(['qr_code' => $qrService->generateSVG($publicUrl, 200)]);
            }
        });
    }

    public static function generateAssetTag(): string
    {
        $prefix = 'BPA';
        $year = date('Y');
        $random = strtoupper(Str::random(6));
        $tag = "{$prefix}-{$year}-{$random}";
        
        while (self::where('asset_tag', $tag)->exists()) {
            $random = strtoupper(Str::random(6));
            $tag = "{$prefix}-{$year}-{$random}";
        }
        
        return $tag;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(AssetMovement::class);
    }

    public function scanLogs(): HasMany
    {
        return $this->hasMany(ScanLog::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'model_id')->where('model_type', self::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', self::STATUS_ASSIGNED);
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    public function scopeRetired($query)
    {
        return $query->where('status', self::STATUS_RETIRED);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'bg-success',
            self::STATUS_ASSIGNED => 'bg-primary',
            self::STATUS_MAINTENANCE => 'bg-warning',
            self::STATUS_RETIRED => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        $path = ltrim((string) $this->image, '/');
        $path = preg_replace('#^storage/#', '', $path);

        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        return route('media.public', ['path' => $path]);
    }

    public function getTotalValueAttribute(): float
    {
        return (float) $this->purchase_cost ?? 0;
    }
}
