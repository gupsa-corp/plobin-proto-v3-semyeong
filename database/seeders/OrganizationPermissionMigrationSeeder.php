<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PermissionCategory;
use App\Models\PermissionGroup;

class OrganizationPermissionMigrationSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // 1. 권한 카테고리 생성
        $this->createPermissionCategories();

        // 2. 기본 권한들 생성
        $this->createPermissions();

        // 3. 역할 생성
        $this->createRoles();

        // 4. 역할별 권한 할당
        $this->assignPermissionsToRoles();
    }

    private function createPermissionCategories(): void
    {
        $categories = [
            [
                'name' => 'member_management',
                'display_name' => '멤버 관리',
                'description' => '조직 멤버 관리 관련 권한',
                'sort_order' => 1,
            ],
            [
                'name' => 'project_management',
                'display_name' => '프로젝트 관리',
                'description' => '프로젝트 생성, 관리, 삭제 관련 권한',
                'sort_order' => 2,
            ],
            [
                'name' => 'billing_management',
                'display_name' => '결제 관리',
                'description' => '결제, 구독, 요금제 관리 관련 권한',
                'sort_order' => 3,
            ],
            [
                'name' => 'organization_settings',
                'display_name' => '조직 설정',
                'description' => '조직 기본 설정 및 관리 권한',
                'sort_order' => 4,
            ],
            [
                'name' => 'permission_management',
                'display_name' => '권한 관리',
                'description' => '역할 및 권한 관리 관련 권한',
                'sort_order' => 5,
            ],
            [
                'name' => 'platform_management',
                'display_name' => '플랫폼 관리',
                'description' => '플랫폼 시스템 관리 관련 권한',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $categoryData) {
            PermissionCategory::firstOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
        }
    }

    private function createPermissions(): void
    {
        // 멤버 관리 권한
        $memberCategory = PermissionCategory::where('name', 'member_management')->first();
        $memberPermissions = [
            ['name' => 'view_members', 'display_name' => '멤버 목록 보기', 'description' => '조직 멤버 목록을 볼 수 있습니다'],
            ['name' => 'invite_members', 'display_name' => '멤버 초대', 'description' => '새로운 멤버를 조직에 초대할 수 있습니다'],
            ['name' => 'manage_member_roles', 'display_name' => '멤버 역할 관리', 'description' => '멤버의 역할을 변경할 수 있습니다'],
            ['name' => 'remove_members', 'display_name' => '멤버 제거', 'description' => '조직에서 멤버를 제거할 수 있습니다'],
        ];

        // 프로젝트 관리 권한
        $projectCategory = PermissionCategory::where('name', 'project_management')->first();
        $projectPermissions = [
            ['name' => 'view_projects', 'display_name' => '프로젝트 보기', 'description' => '조직의 프로젝트를 볼 수 있습니다'],
            ['name' => 'create_projects', 'display_name' => '프로젝트 생성', 'description' => '새로운 프로젝트를 생성할 수 있습니다'],
            ['name' => 'edit_projects', 'display_name' => '프로젝트 편집', 'description' => '프로젝트 정보를 편집할 수 있습니다'],
            ['name' => 'delete_projects', 'display_name' => '프로젝트 삭제', 'description' => '프로젝트를 삭제할 수 있습니다'],
            ['name' => 'manage_project_members', 'display_name' => '프로젝트 멤버 관리', 'description' => '프로젝트 멤버를 관리할 수 있습니다'],
        ];

        // 결제 관리 권한
        $billingCategory = PermissionCategory::where('name', 'billing_management')->first();
        $billingPermissions = [
            ['name' => 'view_billing', 'display_name' => '결제 정보 보기', 'description' => '조직의 결제 정보를 볼 수 있습니다'],
            ['name' => 'manage_billing', 'display_name' => '결제 관리', 'description' => '결제 방법과 구독을 관리할 수 있습니다'],
            ['name' => 'view_billing_history', 'display_name' => '결제 내역 보기', 'description' => '결제 내역을 볼 수 있습니다'],
            ['name' => 'change_subscription', 'display_name' => '구독 변경', 'description' => '구독 플랜을 변경할 수 있습니다'],
        ];

        // 조직 설정 권한
        $orgCategory = PermissionCategory::where('name', 'organization_settings')->first();
        $orgPermissions = [
            ['name' => 'view_organization', 'display_name' => '조직 정보 보기', 'description' => '조직 기본 정보를 볼 수 있습니다'],
            ['name' => 'edit_organization', 'display_name' => '조직 정보 편집', 'description' => '조직 기본 정보를 편집할 수 있습니다'],
            ['name' => 'manage_organization_settings', 'display_name' => '조직 설정 관리', 'description' => '조직의 고급 설정을 관리할 수 있습니다'],
            ['name' => 'delete_organization', 'display_name' => '조직 삭제', 'description' => '조직을 삭제할 수 있습니다'],
        ];

        // 권한 관리 권한
        $permissionCategory = PermissionCategory::where('name', 'permission_management')->first();
        $permissionPermissions = [
            ['name' => 'view_roles', 'display_name' => '역할 보기', 'description' => '조직의 역할을 볼 수 있습니다'],
            ['name' => 'manage_roles', 'display_name' => '역할 관리', 'description' => '역할을 생성, 편집, 삭제할 수 있습니다'],
            ['name' => 'assign_roles', 'display_name' => '역할 할당', 'description' => '멤버에게 역할을 할당할 수 있습니다'],
            ['name' => 'view_permissions', 'display_name' => '권한 보기', 'description' => '권한 목록을 볼 수 있습니다'],
        ];

        // 플랫폼 관리 권한
        $platformCategory = PermissionCategory::where('name', 'platform_management')->first();
        $platformPermissions = [
            ['name' => 'manage_all_organizations', 'display_name' => '모든 조직 목록', 'description' => '플랫폼의 모든 조직을 관리할 수 있습니다'],
            ['name' => 'manage_all_users', 'display_name' => '모든 사용자 관리', 'description' => '플랫폼의 모든 사용자를 관리할 수 있습니다'],
            ['name' => 'manage_system_settings', 'display_name' => '시스템 설정 관리', 'description' => '플랫폼 시스템 설정을 관리할 수 있습니다'],
            ['name' => 'view_system_logs', 'display_name' => '시스템 로그 보기', 'description' => '시스템 로그를 볼 수 있습니다'],
            ['name' => 'manage_platform_permissions', 'display_name' => '플랫폼 권한 관리', 'description' => '플랫폼 전체 권한을 관리할 수 있습니다'],
        ];

        // 권한 생성
        $allPermissions = [
            $memberCategory->id => $memberPermissions,
            $projectCategory->id => $projectPermissions,
            $billingCategory->id => $billingPermissions,
            $orgCategory->id => $orgPermissions,
            $permissionCategory->id => $permissionPermissions,
            $platformCategory->id => $platformPermissions,
        ];

        foreach ($allPermissions as $categoryId => $permissions) {
            foreach ($permissions as $permissionData) {
                Permission::firstOrCreate(
                    ['name' => $permissionData['name']],
                    array_merge($permissionData, [
                        'guard_name' => 'web',
                        'category_id' => $categoryId,
                        'is_active' => true,
                    ])
                );
            }
        }
    }

    private function createRoles(): void
    {
        $roles = [
            // 기본 사용자 (기존 USER)
            [
                'name' => 'user',
                'display_name' => '사용자',
                'description' => '기본 사용자 권한, 프로젝트 참여 및 기본 기능 사용',
                'guard_name' => 'web',
            ],
            // 고급 사용자 (기존 USER_ADVANCED)
            [
                'name' => 'user_advanced',
                'display_name' => '고급 사용자',
                'description' => '고급 사용자 권한, 추가 기능 접근 가능',
                'guard_name' => 'web',
            ],
            // 서비스 매니저 (기존 SERVICE_MANAGER)
            [
                'name' => 'service_manager',
                'display_name' => '서비스 매니저',
                'description' => '서비스 관리 권한, 프로젝트 관리 및 팀 리딩',
                'guard_name' => 'web',
            ],
            // 선임 서비스 매니저 (기존 SERVICE_MANAGER_SENIOR)
            [
                'name' => 'service_manager_senior',
                'display_name' => '선임 서비스 매니저',
                'description' => '선임 서비스 매니저, 고급 프로젝트 관리 권한',
                'guard_name' => 'web',
            ],
            // 조직 목록자 (기존 ORGANIZATION_ADMIN)
            [
                'name' => 'organization_admin',
                'display_name' => '조직 목록자',
                'description' => '조직 목록 권한, 멤버 관리 및 조직 설정',
                'guard_name' => 'web',
            ],
            // 선임 조직 목록자 (기존 ORGANIZATION_ADMIN_SENIOR)
            [
                'name' => 'organization_admin_senior',
                'display_name' => '선임 조직 목록자',
                'description' => '선임 조직 목록자, 고급 조직 목록 권한',
                'guard_name' => 'web',
            ],
            // 조직 소유자 (기존 ORGANIZATION_OWNER)
            [
                'name' => 'organization_owner',
                'display_name' => '조직 소유자',
                'description' => '조직 소유자, 모든 조직 목록 권한',
                'guard_name' => 'web',
            ],
            // 조직 창립자 (기존 ORGANIZATION_OWNER_FOUNDER)
            [
                'name' => 'organization_owner_founder',
                'display_name' => '조직 창립자',
                'description' => '조직 창립자, 최고 조직 권한',
                'guard_name' => 'web',
            ],
            // 플랫폼 관리자 (기존 PLATFORM_ADMIN)
            [
                'name' => 'platform_admin',
                'display_name' => '플랫폼 관리자',
                'description' => '플랫폼 관리자, 시스템 관리 권한',
                'guard_name' => 'web',
            ],
            // 최고 관리자 (기존 PLATFORM_ADMIN_SUPER)
            [
                'name' => 'platform_admin_super',
                'display_name' => '최고 관리자',
                'description' => '최고 관리자, 모든 시스템 권한',
                'guard_name' => 'web',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name'], 'guard_name' => $roleData['guard_name']],
                $roleData
            );
        }
    }

    private function assignPermissionsToRoles(): void
    {
        // 사용자 (기본 권한)
        $userRole = Role::where('name', 'user')->first();
        $userRole->syncPermissions([
            'view_projects',
            'view_organization',
        ]);

        // 고급 사용자
        $userAdvancedRole = Role::where('name', 'user_advanced')->first();
        $userAdvancedRole->syncPermissions([
            'view_projects',
            'view_organization',
            'view_members',
        ]);

        // 서비스 매니저
        $serviceManagerRole = Role::where('name', 'service_manager')->first();
        $serviceManagerRole->syncPermissions([
            'view_projects',
            'create_projects',
            'edit_projects',
            'manage_project_members',
            'view_organization',
            'view_members',
        ]);

        // 선임 서비스 매니저
        $serviceManagerSeniorRole = Role::where('name', 'service_manager_senior')->first();
        $serviceManagerSeniorRole->syncPermissions([
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',
            'manage_project_members',
            'view_organization',
            'view_members',
            'invite_members',
        ]);

        // 조직 목록자
        $organizationAdminRole = Role::where('name', 'organization_admin')->first();
        $organizationAdminRole->syncPermissions([
            // 프로젝트 관리
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',
            'manage_project_members',
            // 멤버 관리
            'view_members',
            'invite_members',
            'manage_member_roles',
            'remove_members',
            // 조직 목록
            'view_organization',
            'edit_organization',
            'manage_organization_settings',
            // 권한 관리
            'view_roles',
            'assign_roles',
            'view_permissions',
        ]);

        // 선임 조직 목록자
        $organizationAdminSeniorRole = Role::where('name', 'organization_admin_senior')->first();
        $organizationAdminSeniorRole->syncPermissions([
            // 모든 조직 목록자 권한 + 추가 권한
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',
            'manage_project_members',
            'view_members',
            'invite_members',
            'manage_member_roles',
            'remove_members',
            'view_organization',
            'edit_organization',
            'manage_organization_settings',
            'view_roles',
            'manage_roles',
            'assign_roles',
            'view_permissions',
            // 결제 정보 보기
            'view_billing',
            'view_billing_history',
        ]);

        // 조직 소유자
        $organizationOwnerRole = Role::where('name', 'organization_owner')->first();
        $organizationOwnerRole->syncPermissions([
            // 모든 조직 관련 권한
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',
            'manage_project_members',
            'view_members',
            'invite_members',
            'manage_member_roles',
            'remove_members',
            'view_organization',
            'edit_organization',
            'manage_organization_settings',
            'delete_organization',
            'view_roles',
            'manage_roles',
            'assign_roles',
            'view_permissions',
            'view_billing',
            'manage_billing',
            'view_billing_history',
            'change_subscription',
        ]);

        // 조직 창립자
        $organizationOwnerFounderRole = Role::where('name', 'organization_owner_founder')->first();
        $organizationOwnerFounderRole->syncPermissions([
            // 모든 조직 소유자 권한 (동일)
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',
            'manage_project_members',
            'view_members',
            'invite_members',
            'manage_member_roles',
            'remove_members',
            'view_organization',
            'edit_organization',
            'manage_organization_settings',
            'delete_organization',
            'view_roles',
            'manage_roles',
            'assign_roles',
            'view_permissions',
            'view_billing',
            'manage_billing',
            'view_billing_history',
            'change_subscription',
        ]);

        // 플랫폼 관리자
        $platformAdminRole = Role::where('name', 'platform_admin')->first();
        $platformAdminRole->syncPermissions([
            // 플랫폼 관리 권한
            'manage_all_organizations',
            'manage_all_users',
            'view_system_logs',
            'manage_platform_permissions',
            // 기본 조직 권한들도 포함
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',
            'manage_project_members',
            'view_members',
            'invite_members',
            'manage_member_roles',
            'remove_members',
            'view_organization',
            'edit_organization',
            'manage_organization_settings',
            'view_roles',
            'manage_roles',
            'assign_roles',
            'view_permissions',
        ]);

        // 최고 관리자
        $platformAdminSuperRole = Role::where('name', 'platform_admin_super')->first();
        $platformAdminSuperRole->syncPermissions(Permission::all()->pluck('name')->toArray());
    }

    /**
     * 기존 enum 값을 새로운 role로 매핑하는 헬퍼 메소드
     */
    public static function mapEnumToRole(int $enumValue): ?string
    {
        return match($enumValue) {
            0 => null, // INVITED - 역할 없음
            100 => 'user', // USER
            150 => 'user_advanced', // USER_ADVANCED
            200 => 'service_manager', // SERVICE_MANAGER
            250 => 'service_manager_senior', // SERVICE_MANAGER_SENIOR
            300 => 'organization_admin', // ORGANIZATION_ADMIN
            350 => 'organization_admin_senior', // ORGANIZATION_ADMIN_SENIOR
            400 => 'organization_owner', // ORGANIZATION_OWNER
            450 => 'organization_owner_founder', // ORGANIZATION_OWNER_FOUNDER
            500 => 'platform_admin', // PLATFORM_ADMIN
            550 => 'platform_admin_super', // PLATFORM_ADMIN_SUPER
            default => null,
        };
    }
}
