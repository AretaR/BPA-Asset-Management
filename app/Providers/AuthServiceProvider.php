<?php

namespace App\Providers;

use App\Models\AssetRequest;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use App\Policies\AssetRequestPolicy;
use App\Policies\AssetPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        AssetRequest::class => AssetRequestPolicy::class,
        Asset::class => AssetPolicy::class,
        Category::class => CategoryPolicy::class,
        Department::class => DepartmentPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (?User $user) {
            if ($user && $user->isSuperAdmin()) {
                return true;
            }
        });
    }
}
