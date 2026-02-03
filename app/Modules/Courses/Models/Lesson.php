<?php

namespace App\Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
      'course_id',
      'title',
      'content',
      'order_index',
      'slug'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
