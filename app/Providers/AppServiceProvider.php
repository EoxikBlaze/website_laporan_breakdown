<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Force HTTPS in production (Railway terminates SSL at proxy level)
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Granular permissions for menus and CRUD
        Gate::define('manage-units', function (User $user) {
            return in_array($user->role, ['super_admin', 'vendor_admin', 'operator']);
        });

        Gate::define('manage-vendors', function (User $user) {
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
