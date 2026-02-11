<?php

use App\Modules\Courses\Http\Controllers\Admin\CourseController;
use App\Modules\Courses\Http\Controllers\Admin\CourseLearnerController;
use App\Modules\Courses\Http\Controllers\Admin\CourseLessonProgressController;
use App\Modules\Courses\Http\Controllers\CourseContinueController;
use App\Modules\Courses\Http\Controllers\CourseEnrollmentController;
use App\Modules\Courses\Http\Controllers\CourseProgressSummaryController;
use App\Modules\Courses\Http\Controllers\MyCoursesController;
use App\Modules\Courses\Http\Controllers\Admin\LessonController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware([
     'auth:api',
     'role:ADMIN'
 ])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::prefix('courses')->group(function () {
            Route::apiResource('/', CourseController::class);

            Route::get('/{course}/lessons', [LessonController::class, 'index']);
            Route::post('/{course}/lessons', [LessonController::class, 'store']);
            Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy']);

            Route::get('/{course}/learners', [CourseLearnerController::class, 'index']);
        });
    });
 });

 Route::prefix('api')->middleware([
     'auth:api',
     'role:MSME_USER',
     'subscription.active',
     'profile.complete',
 ])->group(function () {
     Route::prefix('courses')->group(function () {
         Route::post('/{course}/enroll', [CourseEnrollmentController::class, 'enroll']);
         Route::post('/{course}/lessons/{lesson}/complete', [CourseLessonProgressController::class, 'complete']);
         Route::post('/{course}/progress', [CourseProgressSummaryController::class, 'show']);
     });

     Route::prefix('my-courses')->group(function () {
         Route::get("/", [MyCoursesController::class, 'index']);

         Route::middleware('auth:api')->group(function () {
             Route::get('/continue', [CourseContinueController::class, 'index']);
         });
     });
 });

 Route::prefix('api')->group(function () {
     Route::prefix('courses')->group(function () {
         Route::get('/', [CourseController::class, 'index']);
         Route::get('/{slug}', [CourseController::class, 'show']);
     });
 });
