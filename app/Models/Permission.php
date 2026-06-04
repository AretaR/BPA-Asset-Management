<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    public const SYSTEM_SLUGS = [
        'users.view',
        'users.create',
        'users.edit',
        'users.delete',
        'roles.view',
        'roles.create',
        'roles.edit',
        'roles.delete',
        'permissions.view',
        'permissions.assign',
        'assets.view',
        'assets.create',
        'assets.edit',
        'assets.delete',
        'assets.checkout',
        'assets.checkin',
        'categories.view',
        'categories.create',
        'categories.edit',
        'categories.delete',
        'departments.view',
        'departments.create',
        'departments.edit',
        'departments.delete',
        'reports.view',
        'settings.manage',
        'scanner.access',
        'asset-requests.create',
        'asset-requests.view',
        'asset-requests.approve',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'guard_name',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'permission_user');
    }

    public function isSystemPermission(): bool
    {
        return in_array($this->slug, self::SYSTEM_SLUGS, true);
    }
}
