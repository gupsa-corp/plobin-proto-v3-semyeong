<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\User;
use App\Models\OrganizationMember;
use App\Enums\OrganizationPermission;

class OrganizationMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 테스트용 사용자들 생성
        $users = collect();
        
        // 기존 사용자 확인 또는 생성
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'password' => bcrypt('password'),
            'nickname' => '관리자',
            'first_name' => '관리자',
            'country_code' => 'KR',
            'phone_number' => '01012345678'
        ]);
        $users->push($admin);

        $manager = User::firstOrCreate([
            'email' => 'manager@example.com'
        ], [
            'password' => bcrypt('password'),
            'nickname' => '매니저',
            'first_name' => '매니저',
            'country_code' => 'KR',
            'phone_number' => '01012345679'
        ]);
        $users->push($manager);

        $user1 = User::firstOrCreate([
            'email' => 'user1@example.com'
        ], [
            'password' => bcrypt('password'),
            'nickname' => '사용자1',
            'first_name' => '사용자1',
            'country_code' => 'KR',
            'phone_number' => '01012345680'
        ]);
        $users->push($user1);

        $user2 = User::firstOrCreate([
            'email' => 'user2@example.com'
        ], [
            'password' => bcrypt('password'),
            'nickname' => '사용자2',
            'first_name' => '사용자2',
            'country_code' => 'KR',
            'phone_number' => '01012345681'
        ]);
        $users->push($user2);

        // 테스트 조직 생성 또는 기존 조직 사용
        $organization = Organization::firstOrCreate([
            'name' => '테스트 조직'
        ], [
            'description' => '권한 테스트를 위한 조직',
            'user_id' => $admin->id,
            'status' => 'active',
            'members_count' => 0
        ]);

        // 조직 멤버 추가
        $members = [
            [$admin, OrganizationPermission::ORGANIZATION_OWNER, 'accepted'],
            [$manager, OrganizationPermission::SERVICE_MANAGER, 'accepted'],
            [$user1, OrganizationPermission::USER, 'accepted'],
            [$user2, OrganizationPermission::INVITED, 'pending'],
        ];

        foreach ($members as [$user, $permission, $status]) {
            OrganizationMember::updateOrCreate([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
            ], [
                'permission_level' => $permission->value,
                'invitation_status' => $status,
                'joined_at' => $status === 'accepted' ? now() : null,
                'invited_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // 멤버 수 업데이트
        $organization->update([
            'members_count' => $organization->members()->count()
        ]);

        $this->command->info('Organization members seeded successfully!');
    }
}
