<?php

namespace App\Core\Membership\Providers;

use Illuminate\Support\ServiceProvider;

class MembershipServiceProvider extends ServiceProvider
{
    public function boot() :void
    {
        $this->loadRoutesFrom(base_path().'/app/Core/Membership/routes/api.php');
        $this->loadMigrationsFrom(base_path().'/app/Core/Membership/database/migrations');
    }
}
