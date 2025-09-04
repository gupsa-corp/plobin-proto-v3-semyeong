<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        $this->command->info('🎉 데이터베이스 시딩 완료!');
        $this->command->info('로그인 테스트용 계정:');
        $this->command->info('👤 admin@gupsa.com / password');
    }
}
