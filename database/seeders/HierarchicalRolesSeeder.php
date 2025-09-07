<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class HierarchicalRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 기본 사용자 생성 (없다면)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@platform.com'],
            [
                'name' => 'Platform Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // 테스트용 조직 생성
        $organization = Organization::firstOrCreate(
            ['name' => 'Test Organization'],
            [
                'description' => 'Test organization for role hierarchy',
                'url' => 'test-org',
                'user_id' => $adminUser->id,
            ]
        );

        // 기본 권한들 생성
        $permissions = [
            ['name' => 'view_dashboard', 'category' => 'dashboard'],
            ['name' => 'manage_users', 'category' => 'users'],
            ['name' => 'manage_organizations', 'category' => 'organizations'],
            ['name' => 'manage_projects', 'category' => 'projects'],
            ['name' => 'manage_pages', 'category' => 'pages'],
            ['name' => 'view_reports', 'category' => 'reports'],
            ['name' => 'manage_billing', 'category' => 'billing'],
            ['name' => 'manage_permissions', 'category' => 'permissions'],
        ];

        foreach ($permissions as $permData) {
            Permission::firstOrCreate(
                ['name' => $permData['name']],
                ['guard_name' => 'web', 'category' => $permData['category']]
            );
        }

        // 1. 플랫폼 레벨 역할들
        $platformAdmin = Role::firstOrCreate(
            ['name' => 'Platform Super Admin'],
            [
                'guard_name' => 'web',
                'scope_level' => 'platform',
                'created_by' => $adminUser->id,
                'description' => '플랫폼의 모든 기능과 설정에 대한 최고 관리자 권한',
                'is_active' => true,
            ]
        );

        $platformManager = Role::firstOrCreate(
            ['name' => 'Platform Manager'],
            [
                'guard_name' => 'web', 
                'scope_level' => 'platform',
                'parent_role_id' => $platformAdmin->id,
                'created_by' => $adminUser->id,
                'description' => '플랫폼 운영 관리 권한',
                'is_active' => true,
            ]
        );

        // 2. 조직 레벨 역할들
        $orgOwner = Role::firstOrCreate(
            ['name' => 'Organization Owner'],
            [
                'guard_name' => 'web',
                'scope_level' => 'organization',
                'organization_id' => $organization->id,
                'parent_role_id' => $platformManager->id,
                'created_by' => $adminUser->id,
                'description' => '조직의 최고 관리자 권한',
                'is_active' => true,
            ]
        );

        $orgAdmin = Role::firstOrCreate(
            ['name' => 'Organization Admin'],
            [
                'guard_name' => 'web',
                'scope_level' => 'organization',
                'organization_id' => $organization->id,
                'parent_role_id' => $orgOwner->id,
                'created_by' => $adminUser->id,
                'description' => '조직 관리자 권한',
                'is_active' => true,
            ]
        );

        $orgMember = Role::firstOrCreate(
            ['name' => 'Organization Member'],
            [
                'guard_name' => 'web',
                'scope_level' => 'organization',
                'organization_id' => $organization->id,
                'parent_role_id' => $orgAdmin->id,
                'created_by' => $adminUser->id,
                'description' => '조직 일반 멤버 권한',
                'is_active' => true,
            ]
        );

        // 3. 프로젝트 레벨 역할들 (예시)
        $projectManager = Role::firstOrCreate(
            ['name' => 'Project Manager'],
            [
                'guard_name' => 'web',
                'scope_level' => 'project',
                'organization_id' => $organization->id,
                'project_id' => 1, // 예시 프로젝트 ID
                'parent_role_id' => $orgMember->id,
                'created_by' => $adminUser->id,
                'description' => '프로젝트 관리자 권한',
                'is_active' => true,
            ]
        );

        $projectDeveloper = Role::firstOrCreate(
            ['name' => 'Project Developer'],
            [
                'guard_name' => 'web',
                'scope_level' => 'project',
                'organization_id' => $organization->id,
                'project_id' => 1, // 예시 프로젝트 ID
                'parent_role_id' => $projectManager->id,
                'created_by' => $adminUser->id,
                'description' => '프로젝트 개발자 권한',
                'is_active' => true,
            ]
        );

        // 4. 페이지 레벨 역할들 (예시)
        $pageEditor = Role::firstOrCreate(
            ['name' => 'Page Editor'],
            [
                'guard_name' => 'web',
                'scope_level' => 'page',
                'organization_id' => $organization->id,
                'project_id' => 1, // 예시 프로젝트 ID
                'page_id' => 1, // 예시 페이지 ID
                'parent_role_id' => $projectDeveloper->id,
                'created_by' => $adminUser->id,
                'description' => '특정 페이지 편집 권한',
                'is_active' => true,
            ]
        );

        $pageViewer = Role::firstOrCreate(
            ['name' => 'Page Viewer'],
            [
                'guard_name' => 'web',
                'scope_level' => 'page',
                'organization_id' => $organization->id,
                'project_id' => 1, // 예시 프로젝트 ID
                'page_id' => 1, // 예시 페이지 ID
                'parent_role_id' => $pageEditor->id,
                'created_by' => $adminUser->id,
                'description' => '특정 페이지 열람 권한',
                'is_active' => true,
            ]
        );

        // 권한 할당
        $allPermissions = Permission::all();
        
        // 플랫폼 관리자는 모든 권한
        $platformAdmin->syncPermissions($allPermissions);
        
        // 플랫폼 매니저는 일부 권한
        $platformManager->syncPermissions([
            'view_dashboard', 'manage_users', 'manage_organizations', 'view_reports'
        ]);
        
        // 조직 소유자는 조직 관련 권한
        $orgOwner->syncPermissions([
            'view_dashboard', 'manage_users', 'manage_projects', 'manage_billing'
        ]);
        
        // 조직 관리자는 제한된 권한
        $orgAdmin->syncPermissions([
            'view_dashboard', 'manage_projects', 'manage_pages'
        ]);
        
        // 일반 멤버는 기본 권한
        $orgMember->syncPermissions(['view_dashboard']);
        
        // 프로젝트 매니저는 프로젝트 관련 권한
        $projectManager->syncPermissions(['view_dashboard', 'manage_pages']);
        
        // 개발자는 페이지 관리 권한
        $projectDeveloper->syncPermissions(['view_dashboard', 'manage_pages']);
        
        // 페이지 편집자는 페이지 편집 권한
        $pageEditor->syncPermissions(['view_dashboard']);
        
        // 페이지 뷰어는 열람 권한만
        $pageViewer->syncPermissions(['view_dashboard']);

        $this->command->info('계층형 역할 시드 데이터가 생성되었습니다.');
        $this->command->info("생성된 역할: {$platformAdmin->name}, {$orgOwner->name}, {$projectManager->name}, {$pageEditor->name}");
    }
}