<?php

namespace App\Modules\Courses\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\Course;
use App\Modules\Courses\Models\CourseEnrollment;
use App\Modules\Courses\Models\CourseProgress;
use Illuminate\Http\Request;

class CourseEnrollmentController extends Controller
{
    public function enroll(Request $request, Course $course)
    {
        $user = $request->user();

        if ($course->status !== 'published') {
            return response()->json([
                'message' => 'Course not available for enrollment.'
            ], 403);
        }

        $existing = CourseEnrollment::where([
           'course_id'  => $course->id,
           'user_id'    => $user->id,
        ])->first();

        if ($existing) {
            return response()->json([
                'message' => 'Course already enrolled.',
                'course_id' => $course->id,
                'enroll_date' => $existing->enrolled_at
            ]);
        }

        $enrollment = CourseEnrollment::create([
           'course_id' => $course->id,
           'user_id'    => $user->id,
           'enroll_date' => now()
        ]);

        CourseProgress::firstOrCreate([
            'course_id' => $course->id,
            'user_id' => $user->id,
        ], [
            'percentage' => 0,
            'last_updated_at' => now()
        ]);

        return response()->json([
            'message' => 'Course enrolled successfully.',
            'course_id' => $course->id,
            'enroll_date' => $enrollment->enrolled_at
        ], 201);
    }
}
