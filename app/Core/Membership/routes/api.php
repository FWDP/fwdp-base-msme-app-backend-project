<?php

use App\Core\Auth\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminPaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware(['auth:api'])
        ->get('/current-user', fn(Request $request) => $request->user());

    /*
    |--------------------------------------------------------------------------
    | MSME USER ROUTES
    |--------------------------------------------------------------------------
    | Order matters:
    | 1. auth:api  -> attaches user to request
    | 2. scope     -> validates token permission
    | 3. role      -> validates platform role
    | 4. subscription -> validates access window
    */

    Route::middleware([
        'auth:api',
        'scope:msme-access',
        'role:MSME_USER',
        'subscription.active',
    ])->group(function () {

        // Placeholder for MSME-only features
        Route::get('/msme/dashboard', function () {
            return ['message' => 'MSME dashboard access granted'];
        });

    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware([
        'auth:api',
        'scope:admin-access',
        'role:ADMIN',
    ])->prefix('admin')->group(function () {

        /*
        |----------------------------------------------------------
        | PAYMENT MANAGEMENT (MANUAL)
        |----------------------------------------------------------
        */

        Route::post('/users/{user}/payments',
            [AdminPaymentController::class, 'store']);

        Route::post('/payments/{payment}/confirm',
            [AdminPaymentController::class, 'confirm']);

    });

    /*
    |--------------------------------------------------------------------------
    | MODERATOR ROUTES (FUTURE)
    |--------------------------------------------------------------------------
    */

    Route::middleware([
        'auth:api',
        'scope:moderator-access',
        'role:MODERATOR',
    ])->prefix('moderator')->group(function () {

        Route::get('/health', function () {
            return ['message' => 'Moderator access OK'];
        });

    });
});
