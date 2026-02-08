<?php

namespace App\Modules\Courses\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\Course;
use Illuminate\Http\Request;

class PublicCourseController extends Controller
{
    public function index(Course $course)
    {
        return $course->where('status', 'published')
            ->get(['id', 'title', 'slug', 'description', 'estimated_minutes']);
    }

    public function show(string $slug, Request $request, Course $course)
    {

        $course = $course->where([
                'slug' => $slug,
                'status', 'published'
            ])
            ->with('lessons')
            ->firstOrFail();

        $user = $request->user();

        $isEnrolled = false;

        if ($user) {
            $isEnrolled = $course->enrollments()
                ->where('user_id', $user->id)
                ->exists();
        }

        if ($isEnrolled) {
            $course->setRelation('lessons', $course->lessons->map(function ($lesson) {
                return [
                  'id' => $lesson->id,
                  'title' => $lesson->title,
                  'order_index' => $lesson->order_index,
                ];
            }));
        }

        if ($request->user())
        {
            $course->setRelation('lessons', $course->lessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'order_index' => $lesson->order_index,
                ];
            }));
        }

        return response()->json([
            'course' => $course,
            'is_enrolled' => $isEnrolled
        ]);
    }
}
