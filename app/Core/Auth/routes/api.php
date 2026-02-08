<?php

use App\Core\Auth\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post("/callback", [AuthController::class, 'callback']);
});
