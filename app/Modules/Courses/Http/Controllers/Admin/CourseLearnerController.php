<?php

namespace App\Modules\Courses\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\Course;
use App\Modules\Courses\Models\CourseEnrollment;
use App\Modules\Courses\Models\Lesson;
use Illuminate\Http\Request;
use function Pest\Laravel\get;

class CourseLearnerController extends Controller
{
    public function index($courseId, Course $course)
    {
        $totalLessons = $course->findOrFail($courseId)
            ->lessons()
            ->count();
        $enrollments = $course->findOrFail($courseId)
            ->enrollments()
            ->with([
                'user.profile',
                'progress',
                'lessonCompletions'
            ])
            ->get()
            ->map(function ($enrollment) use ($totalLessons, $courseId) {
                $completedLessons = $enrollment->progress()->value('completed_lessons');

                $progressPercent = $totalLessons > 0
                    ? round(($completedLessons / $totalLessons) * 100)
                    : 0;

                $status = match (true) {
                  $completedLessons === 0 => 'not started',
                  $completedLessons >= $totalLessons => 'completed',
                  default => 'in_progress'
                };

                $lastActivity = $enrollment->lessonCompletions
                    ->sortByDesc('created_at')
                    ->first()?->created_at;

                return [
                    'user_id' => $enrollment->user->id,
                    'name' => ($enrollment->user->profile->first_name ?? '') . ' ' .
                        ($enrollment->user->profile->last_name ?? ''),
                    'email' => $enrollment->user->email,
                    'avatar_url' => $enrollment->user->profile->avatar_url ?? null,
                    'enroll_at' => $enrollment->created_at->toDateTimeString(),
                    'completed_lessons' => $completedLessons,
                    'total_lessons' => $totalLessons,
                    'progress_percent' => $progressPercent,
                    'status' => $status,
                    'last_activity_at' => $lastActivity->toDateTimeString(),
                ];
            });

        $learnerStats = [
            'not_started' => $enrollments->where('status', 'not started')->count(),
            'in_progress' => $enrollments->where('status', 'in_progress')->count(),
            'completed' => $enrollments->where('status', 'completed')->count(),
        ];
        return response()->json([
            'course' => [
                'id' => $courseId,
                'title' => $course->findOrFail($courseId)->title,
                'slug' => $course->findOrFail($courseId)->slug,
                'description' => $course->findOrFail($courseId)->description,
                'total_lessons' => $totalLessons,
                'enrollment_count' => $enrollments->count(),
                'completed_course_count' => $learnerStats['completed'],
            ],
            'learners' => $enrollments,
            'stats'=> $learnerStats,
        ]);
    }
}
