<?php

namespace App\Modules\Courses\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseProgress extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'percentage',
        'completed_lessons',
        'last_updated_at'
    ];

    protected $attributes = [
        'percentage' => 0,
        'completed_lessons' => 0,
    ];

    protected $casts = [
        'last_updated_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
