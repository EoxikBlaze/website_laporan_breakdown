<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Full admin access (for menus, CRUD on master data)
        Gate::define('admin', function (User $user) {
            return $user->role === 'super_admin';
        });

        // Only super_admin can download Excel exports
        Gate::define('can-download', function (User $user) {
            return $user->role === 'super_admin';
        });

        // Only super_admin can manage users
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'super_admin';
        });
    }
}
