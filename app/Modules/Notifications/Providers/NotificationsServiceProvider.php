<?php
namespace App\Modules\Notifications\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        //
    }

    public function boot(): void
    {
        if (! DB::table('modules')
            ->where('name', 'notifications')
            ->where('enabled', 1)
            ->exists()
        ) return;

        $this->loadRoutesFrom(base_path()."/app/Modules/Notifications/routes/api.php");
        $this->loadMigrationsFrom(base_path()."/app/Modules/Notifications/database/migrations");
    }
}
