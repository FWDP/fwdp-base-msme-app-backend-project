<?php

namespace App\Modules\Courses\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\Course;
use App\Modules\Courses\Models\CourseEnrollment;
use App\Modules\Courses\Models\CourseProgress;
use Illuminate\Http\Request;

class CourseEnrollmentController extends Controller
{
    public function enroll(Request $request, Course $course)
    {
        if ($course->status !== 'published') {
            return response()->json([
                'message' => 'Course not available.'
            ], 403);
        }

        CourseEnrollment::create([
            'course_id' => $course->id,
            'user_id' => $request->user()->id,
        ]);

        CourseProgress::firstOrCreate([
           'course_id' => $course->id,
           'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Enrolled.'
        ]);
    }
}
