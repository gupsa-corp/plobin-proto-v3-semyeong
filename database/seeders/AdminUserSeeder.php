<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@plobin.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@plobin.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'nickname' => 'admin-filament',
                'email_verified_at' => now(),
            ]
        );
    }
}
