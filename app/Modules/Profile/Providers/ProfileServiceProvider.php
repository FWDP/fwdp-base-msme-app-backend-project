<?php
namespace App\Modules\Profile\Providers;

use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        //
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(base_path()."/app/Modules/Profile/routes/api.php");
        $this->loadMigrationsFrom(base_path()."/app/Modules/Profile/database/migrations");
    }
}