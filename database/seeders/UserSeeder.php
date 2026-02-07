<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Modules\Profile\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ADMIN USER
        |--------------------------------------------------------------------------
        */
        $admin = User::updateOrCreate(
            ['email' => 'admin@msme.local'],
            [
                'name'      => 'System Admin',
                'password'  => Hash::make('admin123'),
                'role'      => 'ADMIN',
                'is_active' => true,
            ]
        );

        UserProfile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'first_name' => 'System',
                'last_name'  => 'Admin',
                'email'      => 'admin@msme.local',
                'phone'      => '0918000001',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | MODERATOR USER
        |--------------------------------------------------------------------------
        */
        $moderator = User::updateOrCreate(
            ['email' => 'moderator@msme.local'],
            [
                'name'      => 'Content Moderator',
                'password'  => Hash::make('moderator123'),
                'role'      => 'MODERATOR',
                'is_active' => true,
            ]
        );

        UserProfile::firstOrCreate(
            ['user_id' => $moderator->id],
            [
                'first_name' => 'Content',
                'last_name'  => 'Moderator',
                'email'      => 'moderator@msme.local',
                'phone'      => '0918000002',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | MSME USER
        |--------------------------------------------------------------------------
        */
        $msme = User::updateOrCreate(
            ['email' => 'msme@msme.local'],
            [
                'name'      => 'MSME Test User',
                'password'  => Hash::make('msme123'),
                'role'      => 'MSME_USER',
                'is_active' => true,
            ]
        );

        UserProfile::firstOrCreate(
            ['user_id' => $msme->id],
            [
                'first_name' => 'MSME',
                'last_name'  => 'User',
                'email'      => 'msme@msme.local',
                'phone'      => '0918000003',
            ]
        );
    }
}
