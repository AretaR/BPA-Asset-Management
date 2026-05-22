<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use App\Policies\AssetPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Asset::class => AssetPolicy::class,
        Category::class => CategoryPolicy::class,
        Department::class => DepartmentPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
