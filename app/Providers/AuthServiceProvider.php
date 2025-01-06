<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define Permissions

        Gate::define('view-dashboard', function ($user) {
            return $user->hasPermissionTo('view-dashboard');
        });

        Gate::define('make-deposit', function ($user) {
            return $user->hasPermissionTo('make-deposit');
        });

        Gate::define('make-withdrawal', function ($user) {
            return $user->hasPermissionTo('make-withdrawal');
        });

        Gate::define('view-staff', function ($user) {
            return $user->hasPermissionTo('view-staff');
        });

        Gate::define('view-disputes', function ($user) {
            return $user->hasPermissionTo('view-disputes');
        });

        Gate::define('manage-service-provider', function ($user) {
            return $user->hasPermissionTo('manage-service-provider');
        });

        Gate::define('view-stripe', function ($user) {
            return $user->hasPermissionTo('view-stripe');
        });

        Gate::define('view-square', function ($user) {
            return $user->hasPermissionTo('view-square');
        });

        Gate::define('view-checkbook', function ($user) {
            return $user->hasPermissionTo('view-checkbook');
        });

        Gate::define('view-roles-permissions', function ($user) {
            return $user->hasPermissionTo('view-roles-permissions');
        });
    }
}
