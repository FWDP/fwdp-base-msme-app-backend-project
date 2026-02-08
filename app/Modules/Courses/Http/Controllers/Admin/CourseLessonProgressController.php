<?php

namespace App\Modules\Courses\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\Course;
use App\Modules\Courses\Models\CourseEnrollment;
use App\Modules\Courses\Models\CourseProgress;
use App\Modules\Courses\Models\Lesson;
use App\Modules\Courses\Models\LessonCompletion;
use Illuminate\Http\Request;

class CourseLessonProgressController extends Controller
{
    public function complete(Request $request,
                             Lesson $lesson,
                             Course $course)
    {
        if ($lesson->course_id != $course->id) {
            return response()->json([
                'message' => 'This lesson does not belong to this course.'
            ], 400);
        }

        $lesson->completions()->firstOrCreate([
            'lesson_id' => $lesson->id,
            'user_id' => $request->user()->id(),
        ]);

        $percentage = $course->calculateProgressForUser($request->user()->id);

        $course->progress()->updateOrCreate(
            [
                'course_id' => $course->id,
                'user_id' => $request->user()->id(),
            ],
            [
                'percentage' => $percentage,
                'last_updated_at' => now()
            ]
        );

        if ($percentage >= 100)
        {
            $course->enrollments()->where([
                'course_id' => $course->id,
                'user_id' => $request->user()->id(),
            ])->update(['completed_at' => now()]);
        }

        return response()->json([
            'progress' => $percentage
        ]);
    }
}
