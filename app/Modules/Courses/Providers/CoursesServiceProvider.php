<?php
namespace App\Modules\Courses\Providers;

use Illuminate\Support\ServiceProvider;

class CoursesServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        //
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(base_path()."/app/Modules/Courses/routes/api.php");
        $this->loadMigrationsFrom(base_path()."/app/Modules/Courses/database/migrations");
    }
}