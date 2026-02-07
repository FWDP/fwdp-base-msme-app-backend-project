<?php

namespace App\Modules\Courses\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\CourseEnrollment;
use Illuminate\Http\Request;

class MyCoursesController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $enrollments = CourseEnrollment::with(['course'])
            ->where('user_id', $userId)
            ->get();

        return $enrollments->map(function ($enrollment) use ($userId) {
            $course = $enrollment->course;

            $totalLessons = $course->lessons->count();

            $completedLessons = $course->lessons
                ->whereHas('completions', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->count();

            $progress = $totalLessons > 0 ? (int) round(($completedLessons / $totalLessons) * 100) : 0;

            $status = match (true) {
              $enrollment->completed_at !== null => 'completed',
              $completedLessons > 0 => 'in_progress',
              default => 'not_started'
            };

            return [
                'course_id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'enrolled_at' => $enrollment->enrolled_at,
                'completed_at' => $enrollment->completed_at,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'progress_percentage' => $progress,
                'status' => $status
            ];
        });
    }
}
