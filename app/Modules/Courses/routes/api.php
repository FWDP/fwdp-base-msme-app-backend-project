<?php

use App\Modules\Courses\Http\Controllers\Admin\CourseController;
use App\Modules\Courses\Http\Controllers\Admin\CourseEnrollmentController;
use App\Modules\Courses\Http\Controllers\Admin\CourseLessonProgressController;
use Illuminate\Support\Facades\Route;

 Route::prefix('api')->middleware([
     'auth:api',
     'role:ADMIN'
 ])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::prefix('courses')->group(function () {
            Route::apiResource('/', CourseController::class);

            Route::get('/{course}/lessons', [CourseController::class, 'index']);
            Route::post('/{course}/lessons', [CourseController::class, 'store']);
            Route::delete('/lessons/{lesson}', [CourseController::class, 'destroy']);
        });
    });
 });

 Route::prefix('api')->middleware([
     'auth:api',
     'role:MSME_USER',
     'susbscription.active',
     'profile.complete',
 ])->group(function () {
     Route::prefix('courses')->group(function () {
         Route::post('/{course}/enroll', [CourseEnrollmentController::class, 'enroll']);
         Route::post('/{course}/lessons/{lesson}/complete', [CourseLessonProgressController::class, 'complete']);
     });
 });
