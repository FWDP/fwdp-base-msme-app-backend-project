<?php

namespace App\Core\Auth\Http\Controllers;

use App\Core\Auth\Services\PassportService;
use App\Core\Membership\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(
        Request $request,
        User $user,
    )
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $userData = $user->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::MSME_USER->value,
        ]);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('auth_token')->accessToken,
        ]);
    }

    /**
     * @throws ConnectionException
     */
    public function login(
        Request $request,
        PassportService $passportService,
        User $user,
    )
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string'
        ]);

        return response()->json([
            'redirect_to' => '/auth/callback',
            'token_payload' => $passportService->issueToken(
                    $user->where('email', $request->email)->firstOrFail(),
                    $request->query->get('password'),
                )
        ]);
    }

    public function logout(Request $request, PassportService $passportService)
    {
        $passportService->revokeAllTokens($request->user());

        return response()->json([
            'message' => 'You have been successfully logged out!'
        ], 204);
    }

    public function callback(Request $request)
    {
        $accessToken = $request->input("access_token");
        $refreshToken = $request->input("refresh_token");

        if (!$accessToken) {
            return response()->json([
                'error' => 'Invalid token response'
            ], 400);
        }

        return response()->json([
            'auth' => [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer',
                'expires_in' => config('passport.personal_access_token_expires_in', 43200),
            ],
            'user' => $request->user('api')
        ]);
    }
}
