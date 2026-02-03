<?php

namespace App\Modules\Profile\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'phone',
        'avatar_url',
        'avatar_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function getAvatarAttribute()
    {
        if ($this->avatar_path)
        {
            return asset('storage/'.$this->avatar_path);
        }

        return $this->avatar_url ?? null;
    }
}
