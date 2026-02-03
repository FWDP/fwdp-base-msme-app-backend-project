<?php

namespace App\Modules\Profile\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminProfileController extends Controller
{
    public function index()
    {
        return User::with('profile')->paginate(20);
    }

    public function show(User $user)
    {
        return $user->load('profile');
    }
}
