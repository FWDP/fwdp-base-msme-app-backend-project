<?php

namespace App\Modules\Membership\Providers;

use Illuminate\Support\ServiceProvider;

class MembershipServiceProvider extends ServiceProvider
{
    public function boot() :void
    {
        $this->loadRoutesFrom(base_path().'/app/Modules/Membership/routes/api.php');
        $this->loadMigrationsFrom(base_path().'/app/Modules/Membership/database/migrations');
    }
}
