<?php

namespace App\Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
      'title',
      'description',
      'status',
      'estimated_minutes'
    ];

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order_index');
    }

    public function calculateProgressForUser(int $userId): float
    {
        $total = $this->lessons()->count();

        if ($total===0) return 0;

        $completed = LessonCompletion::whereHas('lessons', function ($query) {
            $query->where('course_id', $this->id);
        })->where('user_id', $userId)->count();

        return round(($completed / $total) * 100);
    }
}
