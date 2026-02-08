<?php

namespace App\Core\Auth\Services;

use App\Models\User;
use Http;
use Illuminate\Http\Client\ConnectionException;
use Laravel\Passport\Client;

class PassportService
{
    /**
     * @throws ConnectionException
     */
    public function issueToken(
        User $user,
        string $password
    )
    {
        return Http::asForm()->post('oauth/token', [
            'grant_type' => 'password',
            'client_id' => Client::where('password_client', 1)->first()->id,
            'client_secret' => Client::where('password_client', 1)->first()->secret,
            'username' => $user->email,
            'password' => $password,
            'scope' => '',
        ])->json();
    }

    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}
