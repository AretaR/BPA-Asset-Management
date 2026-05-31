<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected static ?bool $rbacTablesAvailable = null;

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

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_STAFF = 'staff';

    public const ROLES = [
        self::ROLE_SUPER_ADMIN,
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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN || $this->isSuperAdmin();
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function hasRole(string $slug): bool
    {
        if ($this->role === $slug) {
            return true;
        }

        if (!$this->rbacTablesAvailable()) {
            return false;
        }

        return $this->roles()->where('slug', $slug)->exists();
    }

    public function syncAssignedRole(string $slug): void
    {
        $this->role = $slug;
        $this->save();

        if (!$this->rbacTablesAvailable()) {
            return;
        }

        $role = Role::where('slug', $slug)->first();

        if (!$role) {
            return;
        }

        $this->roles()->sync([$role->id]);
    }

    public function hasPermissionTo(string $slug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (!$this->rbacTablesAvailable()) {
            return $this->hasLegacyPermission($slug);
        }

        if ($this->permissions()->where('slug', $slug)->exists()) {
            return true;
        }

        return $this->roles()->whereHas('permissions', fn ($q) => $q->where('slug', $slug))->exists();
    }

    public function getAllPermissionsAttribute()
    {
        if (!$this->rbacTablesAvailable()) {
            return collect();
        }

        return $this->roles->flatMap->permissions->merge($this->permissions)->unique('id');
    }

    public function canAccessAdmin(): bool
    {
        return $this->canManageUsers()
            || $this->canManageAssets()
            || $this->canAssignAssets()
            || $this->canViewReports()
            || $this->canManageSettings();
    }

    public function canManageAssets(): bool
    {
        return $this->hasPermissionTo('assets.create')
            || $this->hasPermissionTo('assets.edit')
            || $this->hasPermissionTo('assets.delete')
            || $this->hasPermissionTo('categories.create')
            || $this->hasPermissionTo('categories.edit')
            || $this->hasPermissionTo('categories.delete')
            || $this->hasPermissionTo('departments.create')
            || $this->hasPermissionTo('departments.edit')
            || $this->hasPermissionTo('departments.delete');
    }

    public function canManageUsers(): bool
    {
        return $this->hasPermissionTo('users.view')
            || $this->hasPermissionTo('users.create')
            || $this->hasPermissionTo('users.edit')
            || $this->hasPermissionTo('users.delete');
    }

    public function canAssignAssets(): bool
    {
        return $this->hasPermissionTo('assets.checkout') || $this->hasPermissionTo('assets.checkin');
    }

    public function canViewReports(): bool
    {
        return $this->hasPermissionTo('reports.view');
    }

    public function canManageSettings(): bool
    {
        return $this->hasPermissionTo('settings.manage');
    }

    public function canManageRbac(): bool
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    protected function rbacTablesAvailable(): bool
    {
        if (self::$rbacTablesAvailable !== null) {
            return self::$rbacTablesAvailable;
        }

        try {
            self::$rbacTablesAvailable = Schema::hasTable('roles')
                && Schema::hasTable('permissions')
                && Schema::hasTable('role_user')
                && Schema::hasTable('permission_role')
                && Schema::hasTable('permission_user');
        } catch (\Throwable) {
            self::$rbacTablesAvailable = false;
        }

        return self::$rbacTablesAvailable;
    }

    protected function hasLegacyPermission(string $slug): bool
    {
        if ($this->isAdmin()) {
            return !in_array($slug, ['roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'permissions.view', 'permissions.assign'], true);
        }

        return in_array($slug, [
            'assets.view',
            'categories.view',
            'departments.view',
            'reports.view',
            'scanner.access',
        ], true);
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match($this->role) {
            self::ROLE_SUPER_ADMIN => 'bg-dark',
            self::ROLE_ADMIN => 'bg-danger',
            self::ROLE_STAFF => 'bg-info',
            default => 'bg-secondary',
        };
    }

    public function getRoleDisplayNameAttribute(): string
    {
        return str($this->role)->replace('_', ' ')->title();
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
