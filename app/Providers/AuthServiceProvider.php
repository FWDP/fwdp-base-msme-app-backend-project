<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();

        /*
        |--------------------------------------------------------------------------
        | Passport Configuration
        |--------------------------------------------------------------------------
        | We are using Laravel Passport for OAuth2 authentication.
        | Token scopes are aligned with platform-level roles.
        | We intentionally keep scopes minimal for MVP safety.
        */

        Passport::tokensCan([
            'msme-access'      => 'Access MSME user APIs',
            'admin-access'     => 'Access administrative APIs',
            'moderator-access' => 'Access moderation APIs',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Default Scope
        |--------------------------------------------------------------------------
        | Applied when a token is created without explicit scopes.
        | MSME access is the safest default.
        */

        Passport::defaultScopes([
            'msme-access',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Token Expiration (Optional but Recommended)
        |--------------------------------------------------------------------------
        | Keep tokens reasonably short-lived.
        | Refresh tokens can be enabled later if needed.
        */

        Passport::tokensExpireIn(now()->addHours(12));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        /*
        |--------------------------------------------------------------------------
        | Authorization Gates (Platform Roles)
        |--------------------------------------------------------------------------
        | These gates provide a clean, centralized way to check user roles.
        | They are optional but very useful for admin logic.
        */

        Gate::define('is-msme-user', function ($user) {
            return $user->role === 'MSME_USER' && $user->is_active;
        });

        Gate::define('is-admin', function ($user) {
            return $user->role === 'ADMIN' && $user->is_active;
        });

        Gate::define('is-moderator', function ($user) {
            return $user->role === 'MODERATOR' && $user->is_active;
        });

        Gate::before(function ($user) {
            return $user->role === 'ADMIN' && $user->is_active ? true : null;
        });
    }
}
