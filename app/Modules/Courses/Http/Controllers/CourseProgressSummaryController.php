<?php

namespace App\Modules\Courses\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\Course;
use App\Modules\Courses\Models\CourseEnrollment;
use Illuminate\Http\Request;

class CourseProgressSummaryController extends Controller
{
    public function show(Request $request, Course $course)
    {
        $userId = $request->user()->id;

        $enrollment = $course->enrollments()
            ->where([
                'user_id', $userId,
                'course_id' => $course->id
            ])
            ->first();

        if (! $enrollment) {
            return response()->json([
                'message' => 'Not enrolled in this course'
            ], 403);
        }

        $totalLessons = $course->lessons()->count();

        $completedLessons = $course->lessons()
            ->whereHas('completions', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->count();

        $progress = $totalLessons > 0 ? (int) round($completedLessons / $totalLessons * 100) : 0;

        $status = match (true) {
          $enrollment->completed_at !== null => 'completed',
          $completedLessons > 0 => 'in_progress',
          default => 'not_started'
        };

        return response()->json([
            'course_id' => $course->id,
            'progress_percentage' => $progress,
            'completed_lessons' => $completedLessons,
            'total_lessons' => $totalLessons,
            'status' => $status
        ]);
    }
}
