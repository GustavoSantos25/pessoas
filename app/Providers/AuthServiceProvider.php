<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        # admin
        Gate::define('admin', function ($user) {
            if ($user->role == 'admin') {
                return true;
            }

            $admins = explode(',', trim(config('pessoas.senhaunica_admins')));
            return in_array($user->codpes, $admins);
        });

        # authorized or admin for permissions
        Gate::define('authorized', function ($user) {
            if ($user->role == 'authorized' || $user->role == 'admin') {
                return true;
            }
        });
    }
}
