<?php

namespace App\Modules\Courses\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\Course;
use App\Modules\Courses\Models\CourseEnrollment;
use Illuminate\Http\Request;

class MyCoursesController extends Controller
{
    public function index(Request $request, CourseEnrollment $enrollments)
    {
        $userId = $request->user()->id;

        return $enrollments->with('course')->where('user_id', $userId)->get()
        ->map(function ($enrollment) use ($userId) {
            $course = $enrollment->course;

            $totalLessons = $course->lessons->count();

            $completedLessons = $enrollment->progress()->value('completed_lessons');

            $progress = $totalLessons > 0 ? (int) round(($completedLessons / $totalLessons) * 100) : 0;

            $status = match (true) {
                $completedLessons === 0 => 'not started',
                $completedLessons >= $totalLessons => 'completed',
                default => 'in_progress'
            };

            return [
                'course_id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'enrolled_at' => $enrollment->created_at->toDateTimeString(),
                'completed_at' => $enrollment->completed_at ?? "",
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'progress_percentage' => $progress,
                'status' => $status
            ];
        });
    }
}
