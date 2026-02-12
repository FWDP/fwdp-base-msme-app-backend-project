<?php

namespace App\Modules\Notifications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Notification extends Model
{
    protected $fillable = [
      'uuid',
      'user_id',
      'type',
      'title',
      'message',
      'data',
      'is_read',
    ];

    protected $casts = [
      'data' => 'array',
      'is_read' => 'boolean',
    ];

    public static function booted(): void
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
