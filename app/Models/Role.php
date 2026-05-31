<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}
