<?php
namespace App\Modules\Courses\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class CoursesServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        //
    }

    public function boot(): void
    {
        if (!DB::table('modules')
            ->where('name', 'courses')
            ->where('enabled', 1)
            ->exists()) {
            return;
        }

        if (DB::table('modules')
            ->where('name', 'core_subscriptions')
            ->where('enabled', 1)
            ->exists()
        ) {
            $this->loadRoutesFrom(base_path()."/app/Modules/Courses/routes/api.php");
        }

        $this->loadMigrationsFrom(base_path()."/app/Modules/Courses/database/migrations");
    }
}
