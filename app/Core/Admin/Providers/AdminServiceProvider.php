<?php

namespace App\Core\Admin\Providers;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(base_path()."/App/Core/Admin/Routes/api.php");
    }
}
