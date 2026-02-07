<?php

namespace App\Modules\Courses\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'enroll_date',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function progress(): HasOne
    {
        return $this
            ->hasOne(CourseProgress::class, 'course_id', 'course_id')
            ->where('user_id', $this->user_id);
    }

    public function lessonCompletions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class, 'user_id', 'user_id');
    }
}
