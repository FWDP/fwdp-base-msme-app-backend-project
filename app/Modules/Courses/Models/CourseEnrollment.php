<?php

namespace App\Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    //
    protected $fillable = [
        'course_id',
        'user_id',
    ];
}
