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

        // 테스트용 관리자 계정 생성 - using direct DB operations
        $admin = \DB::table('users')->where('email', 'admin@example.com')->first();
        if (!$admin) {
            \DB::table('users')->insert([
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'country_code' => '+82',
                'phone_number' => '01012345678',
                'nickname' => 'admin',
                'first_name' => '관리자',
                'last_name' => '테스트',
                'name' => '관리자 테스트', // Add name field
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $admin = \DB::table('users')->where('email', 'admin@example.com')->first();
        }

        // admin@example.com에 platform_admin 권한 부여 - using direct DB operations
        $platformAdminRole = \DB::table('roles')->where('name', 'platform_admin')->first();
        if ($platformAdminRole && $admin) {
            \DB::table('model_has_roles')->insert([
                'role_id' => $platformAdminRole->id,
                'model_type' => 'App\Models\User',
                'model_id' => $admin->id,
            ]);
        }

        // 테스트용 조직 생성 - using direct DB operations
        $organization = \DB::table('organizations')->where('name', '테스트 조직')->first();
        if (!$organization) {
            \DB::table('organizations')->insert([
                'name' => '테스트 조직',
                'description' => '개발 및 테스트용 조직입니다.',
                'user_id' => $admin->id,
                'status' => 'active',
                'members_count' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $organization = \DB::table('organizations')->where('name', '테스트 조직')->first();
        }

        // 조직 멤버 추가 - using direct DB operations
        $existingMember = \DB::table('organization_members')
            ->where('organization_id', $organization->id)
            ->where('user_id', $admin->id)
            ->first();
            
        if (!$existingMember) {
            \DB::table('organization_members')->insert([
                'organization_id' => $organization->id,
                'user_id' => $admin->id,
                'role_name' => 'owner',
                'invitation_status' => 'accepted',
                'joined_at' => now(),
                'invited_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 테스트용 프로젝트 생성 - using direct DB operations
        $existingProject = \DB::table('projects')->where('name', '테스트 프로젝트')->first();
        if (!$existingProject) {
            \DB::table('projects')->insert([
                'name' => '테스트 프로젝트',
                'description' => '개발 및 테스트용 프로젝트입니다.',
                'organization_id' => $organization->id,
                'user_id' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 구독 및 결제 관련 시딩
        $this->call([
            SubscriptionSeeder::class,
            PaymentMethodSeeder::class,
            BillingHistorySeeder::class,
        ]);

        $this->command->info('🎉 데이터베이스 시딩 완료!');
        $this->command->info('로그인 테스트용 계정:');
        $this->command->info('👤 admin@example.com / password (platform_admin 권한)');
        $this->command->info('🏢 테스트 조직 생성됨');
        $this->command->info('📁 테스트 프로젝트 생성됨');
        $this->command->info('💳 결제 및 구독 데이터 생성됨');
    }
}
