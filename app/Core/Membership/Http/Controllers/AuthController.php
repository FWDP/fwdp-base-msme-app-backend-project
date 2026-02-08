<?php

namespace App\Core\Membership\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::updateOrCreate([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role'=>'MSME_USER'
        ]);

        $trial = SubscriptionPlan::trial();

        Subscription::updateOrCreate([
            'user_id'=>$user->id,
            'plan_id'=>$trial->id,
            'status'=>'trial',
            'start_date'=>now(),
            'end_date'=>now()->addDays($trial->duration_days),
        ]);

        return response()->json([
            'access_token'=>$user
                ->createToken('msme-token',['msme-access'])
                ->accessToken
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Account is inactive'
            ], 403);
        }

        $scopes = match ($user->role) {
            'ADMIN' => ['admin-access'],
            'MODERATOR' => ['moderator-access'],
            default => ['msme-access'],
        };

        $token = $user->createToken('access-token', $scopes)->accessToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'role'         => $user->role,
        ]);
    }
}
