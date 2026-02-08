<?php

use App\Http\Controllers\Admin\DashboardController;

Route::prefix('api')->middleware(["auth:api", "role:ADMIN|MODERATOR"])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });
});
