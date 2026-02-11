<?php

namespace App\Modules\Courses\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\CourseEnrollment;
use Illuminate\Http\Request;

class CourseContinueController extends Controller
{
    public function index(Request $request, CourseEnrollment $enrollments)
    {
        $user = $request->user();

        $enrollments = $enrollments
            ->where('user_id', $user->id)
            ->with([
                'course.lessons',
                'lessonCompletions',
                'progress'
            ])
            ->latest('updated_at')
            ->get();

        if ($enrollments->isEmpty()) {
            return response()->json([
                'message' => 'No active courses found'
            ], 404);
        }

        return response()->json([
            'message' => 'You have following pending courses',
            'data' => $enrollments->map(function ($enrollment) {
                $course = $enrollment->course;
                $lessons = $course->lessons;

                $completedLessonIds = $enrollment->lessonCompletions
                    ->pluck('id')
                    ->unique()
                    ->values();

                $nextLesson = $lessons->whereNotIn('id', $completedLessonIds)
                    ->sortBy('order')
                    ->first();

                if (!$nextLesson) {
                    return response()->json([
                        'course' => [
                            'id' => $course->id,
                            'title' => $course->title,
                            'slug' => $course->slug,
                        ],
                        'status'  => 'completed',
                        'message' => 'You have completed this course.'
                    ]);
                }

                return response()->json([
                    'course' => [
                        'id' => $course->id,
                        'title' => $course->title,
                        'slug' => $course->slug,
                        'progress_percentage' => $course->progress_percentage ?? 0,
                    ],
                    'next_lesson' => [
                        'id' => $nextLesson->id,
                        'title' => $nextLesson->title,
                        'order' => $nextLesson->order_index
                    ],
                    'status'  => 'in_progress',
                    'last_activity_at' => $enrollment->updated_at->toDateTimeString(),
                ]);
            })
        ]);
    }
}
