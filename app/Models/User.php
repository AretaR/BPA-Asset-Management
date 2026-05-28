<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'employee_id',
        'department_id',
        'phone',
        'position',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public const ROLE_ADMIN = 'admin';
    public const ROLE_STAFF = 'staff';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_STAFF,
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedAssets(): HasMany
    {
        return $this->hasMany(Asset::class, 'assigned_to');
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function canAccessAdmin(): bool
    {
        return $this->isAdmin();
    }

    public function canManageAssets(): bool
    {
        return $this->isAdmin();
    }

    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    public function canAssignAssets(): bool
    {
        return $this->isAdmin();
    }

    public function canViewReports(): bool
    {
        return $this->isAdmin();
    }

    public function canManageSettings(): bool
    {
        return $this->isAdmin();
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'bg-danger',
            self::ROLE_STAFF => 'bg-info',
            default => 'bg-secondary',
        };
    }

    public function getAssetsCountAttribute(): int
    {
        return $this->assignedAssets()->count();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Generate a local SVG initials avatar — no external dependencies
        $words = explode(' ', trim($this->name));
        $initials = strtoupper(substr($words[0], 0, 1));
        if (count($words) > 1) {
            $initials .= strtoupper(substr(end($words), 0, 1));
        }

        // Pick a consistent colour from the user's name
        $colours = [
            '#0D8ABC', '#6366f1', '#8b5cf6', '#ec4899',
            '#f59e0b', '#10b981', '#3b82f6', '#ef4444',
        ];
        $colour = $colours[ord($this->name[0]) % count($colours)];

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200">'
            . '<rect width="200" height="200" fill="' . $colour . '"/>'
            . '<text x="100" y="115" text-anchor="middle" font-family="Arial,sans-serif" '
            . 'font-size="80" font-weight="bold" fill="#ffffff">' . $initials . '</text>'
            . '</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
