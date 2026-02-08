<?php

namespace App\Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    protected $fillable = [
        'title',
        'description',
        "cover_image_url",
        'status',
        'estimated_minutes',
        'course_id',
        'user_id',
        'percentage',
        'last_updated_at',
    ];

    public function enrollments() : HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function lessons() : HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(CourseProgress::class);
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
