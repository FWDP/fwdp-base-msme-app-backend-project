<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Profile\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MsmEUserWithProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $firstName = "MSME{$i}";
            $lastName = "User{$i}";
            $email = "{$firstName}@msme.me";

            $user = User::create([
                'name' => "$firstName $lastName",
                'email' => $email,
                'password' => Hash::make('password'),
                'role'  => 'MSME_USER',
                'is_active' => true,
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => "0917" . str_pad($i, 6, '0', STR_PAD_LEFT),
                'avatar_url' => null,
                'gender' => 'M',
            ]);
        }
    }
}
