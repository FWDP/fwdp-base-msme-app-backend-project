<?php

namespace App\Core\Subscriptions\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class SubscriptionsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (!DB::table('modules')
            ->where('name', 'core_subscriptions')
            ->where('enabled', true)
            ->exists()) {
            return;
        }

        $this->loadRoutesFrom(base_path()."/app/Core/Subscriptions/routes/api.php");
        $this->loadMigrationsFrom(base_path()."/app/Core/Subscriptions/database/migrations");
    }

    public function register(): void
    {

    }
}
