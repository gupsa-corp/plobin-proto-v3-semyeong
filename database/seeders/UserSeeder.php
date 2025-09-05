<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin 사용자 생성
        User::updateOrCreate(
            ['email' => 'admin@gupsa.com'],
            [
                'email' => 'admin@gupsa.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'country_code' => '+82',
                'phone_number' => '01012345678',
                'nickname' => 'admin',
                'first_name' => '김',
                'last_name' => '준',
            ]
        );

        $this->command->info('사용자 시딩 완료:');
        $this->command->info('- admin@gupsa.com / password');
    }
}
