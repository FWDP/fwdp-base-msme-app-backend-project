<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
        User::updateOrCreate(
            ['email' => 'admin@msme.local'],
            [
                'name'      => 'System Admin',
                'password'  => Hash::make('admin123'),
                'role'      => 'ADMIN',
                'is_active' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | MODERATOR USER
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => 'moderator@msme.local'],
            [
                'name'      => 'Content Moderator',
                'password'  => Hash::make('moderator123'),
                'role'      => 'MODERATOR',
                'is_active' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | MSME USER
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => 'msme@msme.local'],
            [
                'name'      => 'MSME Test User',
                'password'  => Hash::make('msme123'),
                'role'      => 'MSME_USER',
                'is_active' => true,
            ]
        );
    }
}
