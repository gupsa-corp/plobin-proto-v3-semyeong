<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 권한 및 역할 시딩 먼저 실행
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            PricingPlanSeeder::class,
            SandboxProjectPagesSeeder::class,
        ]);

        // 테스트용 관리자 계정 생성
        $admin = User::updateOrCreate(
            ['email' => 'admin@gupsa.com'],
            [
                'email' => 'admin@gupsa.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'country_code' => '+82',
                'phone_number' => '01012345678',
                'nickname' => 'admin',
                'first_name' => '관리자',
                'last_name' => '테스트',
            ]
        );

        // admin@gupsa.com에 platform_admin 권한 부여
        $admin->assignRole('platform_admin');

        // 테스트용 조직 생성
        $organization = Organization::updateOrCreate(
            ['name' => '테스트 조직'],
            [
                'name' => '테스트 조직',
                'description' => '개발 및 테스트용 조직입니다.',
                'user_id' => $admin->id,
                'status' => 'active',
                'members_count' => 1
            ]
        );

        // 조직 멤버 추가
        OrganizationMember::updateOrCreate(
            [
                'organization_id' => $organization->id,
                'user_id' => $admin->id,
            ],
            [
                'role_name' => 'owner',
                'invitation_status' => 'accepted',
                'joined_at' => now(),
                'invited_at' => now(),
            ]
        );

        // 테스트용 프로젝트 생성
        Project::updateOrCreate(
            ['name' => '테스트 프로젝트'],
            [
                'name' => '테스트 프로젝트',
                'description' => '개발 및 테스트용 프로젝트입니다.',
                'organization_id' => $organization->id,
                'user_id' => $admin->id,
            ]
        );

        // 구독 및 결제 관련 시딩
        $this->call([
            SubscriptionSeeder::class,
            PaymentMethodSeeder::class,
            BillingHistorySeeder::class,
        ]);

        $this->command->info('🎉 데이터베이스 시딩 완료!');
        $this->command->info('로그인 테스트용 계정:');
        $this->command->info('👤 admin@gupsa.com / password (platform_admin 권한)');
        $this->command->info('🏢 테스트 조직 생성됨');
        $this->command->info('📁 테스트 프로젝트 생성됨');
        $this->command->info('💳 결제 및 구독 데이터 생성됨');
    }
}
