<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Schema;

class Role extends Model
{
    public const SYSTEM_SLUGS = [
        User::ROLE_SUPER_ADMIN,
        User::ROLE_ADMIN,
        User::ROLE_STAFF,
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'guard_name',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function isSystemRole(): bool
    {
        return in_array($this->slug, self::SYSTEM_SLUGS, true);
    }

    public function hasPermission(string $slug): bool
    {
        return $this->permissions()->where('slug', $slug)->exists();
    }

    public static function availableOptions(): Collection
    {
        try {
            if (Schema::hasTable('roles')) {
                $roles = static::query()->orderBy('name')->get();

                if ($roles->isNotEmpty()) {
                    return $roles;
                }
            }
        } catch (\Throwable) {
            // Fall back to built-in roles when RBAC tables are unavailable.
        }

        return collect([
            new static(['name' => 'Super Admin', 'slug' => User::ROLE_SUPER_ADMIN]),
            new static(['name' => 'Admin', 'slug' => User::ROLE_ADMIN]),
            new static(['name' => 'Staff', 'slug' => User::ROLE_STAFF]),
        ]);
    }
}
