<?php

namespace App\Modules\Courses\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\Course;
use Illuminate\Http\Request;

class PublicCourseController extends Controller
{
    public function index()
    {
        return Course::where('status', 'published')
            ->select(['id', 'title','slug','description','estimated_minutes'])
            ->get();
    }

    public function show(string $slug, Request $request)
    {
        $course = Course::where('slug', $slug)
            ->where('status', 'published')
            ->with(['lessons:id,course_id,title,order_index'])
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
