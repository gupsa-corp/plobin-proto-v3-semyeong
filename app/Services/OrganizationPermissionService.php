<?php

namespace App\Services;

use App\Models\User;
use App\Models\Organization;
use App\Models\OrganizationMember;
use Spatie\Permission\Models\Role;
use App\Enums\OrganizationPermission;

/**
 * OrganizationPermission enum을 Spatie Laravel Permission으로 마이그레이션하기 위한 서비스
 * 기존 enum 메소드들과 호환성을 유지하면서 새로운 권한 시스템을 사용
 */
class OrganizationPermissionService
{
    /**
     * 기존 enum 값을 role 이름으로 변환
     */
    public static function enumToRole(int $enumValue): ?string
    {
        return match($enumValue) {
            0 => null, // INVITED
            100 => 'user',
            150 => 'user_advanced', 
            200 => 'service_manager',
            250 => 'service_manager_senior',
            300 => 'organization_admin',
            350 => 'organization_admin_senior',
            400 => 'organization_owner',
            450 => 'organization_owner_founder',
            500 => 'platform_admin',
            550 => 'platform_admin_super',
            default => null,
        };
    }

    /**
     * role 이름을 enum 값으로 변환 (역호환성)
     */
    public static function roleToEnum(string $roleName): int
    {
        return match($roleName) {
            'user' => 100,
            'user_advanced' => 150,
            'service_manager' => 200,
            'service_manager_senior' => 250,
            'organization_admin' => 300,
            'organization_admin_senior' => 350,
            'organization_owner' => 400,
            'organization_owner_founder' => 450,
            'platform_admin' => 500,
            'platform_admin_super' => 550,
            default => 0,
        };
    }

    /**
     * 사용자의 조직 내 권한 레벨 가져오기 (기존 enum value 형태)
     */
    public static function getUserPermissionLevel(User $user, Organization $organization): int
    {
        $member = OrganizationMember::where('user_id', $user->id)
            ->where('organization_id', $organization->id)
            ->first();

        if (!$member) {
            return 0; // INVITED 또는 비멤버
        }

        // 조직 컨텍스트에서 사용자의 역할 가져오기
        $roles = $user->getRoleNames(); // Spatie 메소드
        
        // 가장 높은 권한 레벨 찾기
        $maxLevel = 0;
        foreach ($roles as $roleName) {
            $level = self::roleToEnum($roleName);
            $maxLevel = max($maxLevel, $level);
        }

        return $maxLevel;
    }

    /**
     * 권한 체크 메소드들 (기존 enum 메소드와 동일한 시그니처)
     */
    public static function hasPermission(User $user, Organization $organization, int $requiredLevel): bool
    {
        $userLevel = self::getUserPermissionLevel($user, $organization);
        return $userLevel >= $requiredLevel;
    }

    public static function canManageMembers(User $user, Organization $organization): bool
    {
        return $user->hasPermissionTo('manage_member_roles') || 
               $user->hasPermissionTo('remove_members') ||
               self::hasPermission($user, $organization, OrganizationPermission::ORGANIZATION_ADMIN->value);
    }

    public static function canManagePermissions(User $user, Organization $organization): bool
    {
        return $user->hasPermissionTo('manage_roles') || 
               $user->hasPermissionTo('assign_roles') ||
               self::hasPermission($user, $organization, OrganizationPermission::ORGANIZATION_ADMIN->value);
    }

    public static function canManageBilling(User $user, Organization $organization): bool
    {
        return $user->hasPermissionTo('manage_billing') ||
               self::hasPermission($user, $organization, OrganizationPermission::ORGANIZATION_OWNER->value);
    }

    public static function canManageProjects(User $user, Organization $organization): bool
    {
        return $user->hasPermissionTo('create_projects') || 
               $user->hasPermissionTo('edit_projects') ||
               self::hasPermission($user, $organization, OrganizationPermission::SERVICE_MANAGER->value);
    }

    public static function canDeleteOrganization(User $user, Organization $organization): bool
    {
        return $user->hasPermissionTo('delete_organization') ||
               self::hasPermission($user, $organization, OrganizationPermission::ORGANIZATION_OWNER->value);
    }

    /**
     * 역할의 표시 정보 가져오기 (기존 enum 메소드와 호환)
     */
    public static function getRoleDisplayInfo(string $roleName): array
    {
        return match($roleName) {
            'user' => [
                'label' => '사용자',
                'short_label' => '사용자',
                'description' => '기본 사용자 권한, 프로젝트 참여 및 기본 기능 사용',
                'color' => 'blue',
                'level' => 1,
            ],
            'user_advanced' => [
                'label' => '고급 사용자',
                'short_label' => '사용자+',
                'description' => '고급 사용자 권한, 추가 기능 접근 가능',
                'color' => 'blue',
                'level' => 1,
            ],
            'service_manager' => [
                'label' => '서비스 매니저',
                'short_label' => '서비스 매니저',
                'description' => '서비스 관리 권한, 프로젝트 관리 및 팀 리딩',
                'color' => 'green',
                'level' => 2,
            ],
            'service_manager_senior' => [
                'label' => '선임 서비스 매니저',
                'short_label' => '서비스 매니저+',
                'description' => '선임 서비스 매니저, 고급 프로젝트 관리 권한',
                'color' => 'green',
                'level' => 2,
            ],
            'organization_admin' => [
                'label' => '조직 관리자',
                'short_label' => '관리자',
                'description' => '조직 관리 권한, 멤버 관리 및 조직 설정',
                'color' => 'purple',
                'level' => 3,
            ],
            'organization_admin_senior' => [
                'label' => '선임 조직 관리자',
                'short_label' => '관리자+',
                'description' => '선임 조직 관리자, 고급 조직 관리 권한',
                'color' => 'purple',
                'level' => 3,
            ],
            'organization_owner' => [
                'label' => '조직 소유자',
                'short_label' => '소유자',
                'description' => '조직 소유자, 모든 조직 관리 권한',
                'color' => 'red',
                'level' => 4,
            ],
            'organization_owner_founder' => [
                'label' => '조직 창립자',
                'short_label' => '창립자',
                'description' => '조직 창립자, 최고 조직 권한',
                'color' => 'red',
                'level' => 4,
            ],
            'platform_admin' => [
                'label' => '플랫폼 관리자',
                'short_label' => '플랫폼 관리자',
                'description' => '플랫폼 관리자, 시스템 관리 권한',
                'color' => 'gray',
                'level' => 5,
            ],
            'platform_admin_super' => [
                'label' => '최고 관리자',
                'short_label' => '최고 관리자',
                'description' => '최고 관리자, 모든 시스템 권한',
                'color' => 'gray',
                'level' => 5,
            ],
            default => [
                'label' => $roleName,
                'short_label' => $roleName,
                'description' => '사용자 정의 역할',
                'color' => 'indigo',
                'level' => 999,
            ]
        };
    }

    /**
     * 레벨별 역할 그룹 가져오기
     */
    public static function getAllRolesByLevel(): array
    {
        return [
            0 => [], // 초대됨 (역할 없음)
            1 => ['user', 'user_advanced'],
            2 => ['service_manager', 'service_manager_senior'],
            3 => ['organization_admin', 'organization_admin_senior'],
            4 => ['organization_owner', 'organization_owner_founder'],
            5 => ['platform_admin', 'platform_admin_super'],
        ];
    }

    /**
     * 선택 옵션을 위한 역할 목록 가져오기
     */
    public static function getRoleSelectOptions(): array
    {
        $options = [];
        $roles = Role::all();
        
        foreach ($roles as $role) {
            $displayInfo = self::getRoleDisplayInfo($role->name);
            $options[$role->name] = $displayInfo['label'];
        }

        return $options;
    }

    /**
     * 기존 organization_members 테이블의 permission_level을 새로운 역할 시스템으로 마이그레이션
     */
    public static function migrateOrganizationMember(OrganizationMember $member): void
    {
        $user = $member->user;
        $roleName = self::enumToRole($member->permission_level);
        
        if ($roleName && !$user->hasRole($roleName)) {
            $user->assignRole($roleName);
        }
    }

    /**
     * 모든 조직 멤버의 권한을 마이그레이션
     */
    public static function migrateAllOrganizationMembers(): void
    {
        OrganizationMember::with('user')->chunk(100, function ($members) {
            foreach ($members as $member) {
                self::migrateOrganizationMember($member);
            }
        });
    }
}