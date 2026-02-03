<?php

namespace App\Modules\Profile\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('profile');

        // --- Search by user fields ---
        if ($request->filled('q')) {
            $q = $request->q;

            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        return $query->paginate(20);
    }

    public function show(User $user)
    {
        return $user->load('profile');
    }
}
