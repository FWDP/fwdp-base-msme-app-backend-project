<?php
 use Illuminate\Support\Facades\Route;
 use App\Modules\Notifications\Http\Controllers\NotificationController;

 Route::prefix('api')->group(function () {
    Route::prefix('notifications')->middleware('auth:api')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/{notification}/read', [NotificationController::class, 'markRead']);
        Route::get('/health', function () {
            return response()->json([
                'module' => 'notifications',
                'status' => 200
            ]);
        });

        Route::middleware(['auth:api', 'role:ADMIN'])->group(function () {
            Route::post('/broadcast', [NotificationController::class, 'broadcast']);
        });
    });
 });
