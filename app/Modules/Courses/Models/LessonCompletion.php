<?php

namespace App\Modules\Courses\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonCompletion extends Model
{
    protected $fillable = [
      'lesson_id',
      'user_id',
      'completed_at',
    ];

    protected $casts = [
      'completed_at' => 'datetime',
    ];

    public function lesson() : BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
