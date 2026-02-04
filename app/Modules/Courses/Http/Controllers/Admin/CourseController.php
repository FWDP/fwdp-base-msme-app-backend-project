<?php

namespace App\Modules\Courses\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Courses\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return Course::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'in:draft|published',
        ]);

        return Course::create($request->only([
            'title', 'description', 'status', 'estimated_minutes'
        ]));
    }

    public function show(Course $course)
    {
        return $course;
    }

    public function update(Request $request, Course $course)
    {
        $course->update($request->only([
            'title', 'description', 'status', 'estimated_minutes'
        ]));

        return $course;
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return response()->noContent();
    }
}
