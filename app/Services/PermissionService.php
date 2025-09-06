<?php

namespace App\Services;

use App\Enums\OrganizationPermission;

class PermissionService
{
    /**
     * 권한 레벨별로 가능한 작업들을 정의
     */
    public static function getPermissionMatrix(): array
    {
        return [
            'member_management' => [
                'view' => [OrganizationPermission::SERVICE_MANAGER->value, '멤버 목록 보기'],
                'invite' => [OrganizationPermission::ORGANIZATION_ADMIN->value, '멤버 초대'],
                'edit' => [OrganizationPermission::ORGANIZATION_ADMIN->value, '멤버 정보 수정'],
                'delete' => [OrganizationPermission::ORGANIZATION_OWNER->value, '멤버 삭제'],
                'change_permission' => [OrganizationPermission::ORGANIZATION_ADMIN->value, '권한 변경'],
            ],
            'project_management' => [
                'view' => [OrganizationPermission::USER->value, '프로젝트 보기'],
                'create' => [OrganizationPermission::SERVICE_MANAGER->value, '프로젝트 생성'],
                'edit' => [OrganizationPermission::SERVICE_MANAGER->value, '프로젝트 수정'],
                'delete' => [OrganizationPermission::ORGANIZATION_ADMIN->value, '프로젝트 삭제'],
                'assign_members' => [OrganizationPermission::SERVICE_MANAGER->value, '멤버 배정'],
            ],
            'billing_management' => [
                'view' => [OrganizationPermission::ORGANIZATION_ADMIN->value, '결제 정보 보기'],
                'edit' => [OrganizationPermission::ORGANIZATION_OWNER->value, '결제 정보 수정'],
                'download_receipts' => [OrganizationPermission::ORGANIZATION_ADMIN->value, '영수증 다운로드'],
                'change_plan' => [OrganizationPermission::ORGANIZATION_OWNER->value, '요금제 변경'],
            ],
            'organization_settings' => [
                'view' => [OrganizationPermission::SERVICE_MANAGER->value, '조직 정보 보기'],
                'edit' => [OrganizationPermission::ORGANIZATION_ADMIN->value, '조직 정보 수정'],
                'delete' => [OrganizationPermission::ORGANIZATION_OWNER->value, '조직 삭제'],
            ],
            'permission_management' => [
                'view' => [OrganizationPermission::SERVICE_MANAGER->value, '권한 현황 보기'],
                'edit' => [OrganizationPermission::ORGANIZATION_ADMIN->value, '권한 수정'],
                'create_role' => [OrganizationPermission::ORGANIZATION_ADMIN->value, '역할 생성'],
                'delete_role' => [OrganizationPermission::ORGANIZATION_OWNER->value, '역할 삭제'],
            ]
        ];
    }

    /**
     * 특정 권한이 특정 작업을 수행할 수 있는지 확인
     */
    public static function canPerformAction(
        OrganizationPermission $userPermission, 
        string $category, 
        string $action
    ): bool {
        $matrix = self::getPermissionMatrix();
        
        if (!isset($matrix[$category][$action])) {
            return false;
        }

        $requiredPermissionValue = $matrix[$category][$action][0];
        return $userPermission->value >= $requiredPermissionValue;
    }

    /**
     * 권한별로 접근 가능한 기능 목록 반환
     */
    public static function getAvailableFeatures(OrganizationPermission $permission): array
    {
        $features = [];
        $matrix = self::getPermissionMatrix();

        foreach ($matrix as $category => $actions) {
            $availableActions = [];
            foreach ($actions as $action => $requirements) {
                if ($permission->value >= $requirements[0]) {
                    $availableActions[$action] = $requirements[1];
                }
            }
            if (!empty($availableActions)) {
                $features[$category] = $availableActions;
            }
        }

        return $features;
    }

    /**
     * 권한 업그레이드 가능 여부 확인
     */
    public static function canUpgradePermission(
        OrganizationPermission $currentPermission,
        OrganizationPermission $targetPermission,
        OrganizationPermission $actorPermission
    ): bool {
        // 자신보다 높은 권한을 부여할 수 없음
        if ($targetPermission->value >= $actorPermission->value) {
            return false;
        }

        // 플랫폼 관리자만이 플랫폼 관리자 권한을 부여할 수 있음
        if ($targetPermission->value >= OrganizationPermission::PLATFORM_ADMIN->value) {
            return $actorPermission->value >= OrganizationPermission::PLATFORM_ADMIN->value;
        }

        // 조직 소유자만이 조직 소유자 권한을 부여할 수 있음
        if ($targetPermission->value >= OrganizationPermission::ORGANIZATION_OWNER->value) {
            return $actorPermission->value >= OrganizationPermission::ORGANIZATION_OWNER->value;
        }

        // 조직 관리자는 조직 관리자까지만 부여 가능
        if ($actorPermission->value >= OrganizationPermission::ORGANIZATION_ADMIN->value) {
            return $targetPermission->value < OrganizationPermission::ORGANIZATION_OWNER->value;
        }

        return false;
    }

    /**
     * 권한별 사이드바 메뉴 필터링
     */
    public static function filterSidebarMenu(OrganizationPermission $permission): array
    {
        $menuItems = [];

        // 회원 관리
        if ($permission->canManageMembers()) {
            $menuItems[] = [
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                          </svg>',
                'title' => '회원 관리',
                'url' => '/organizations/1/admin/members',
                'active' => request()->is('organizations/*/admin/members'),
                'description' => '조직 구성원 관리'
            ];
        }

        // 권한 관리
        if ($permission->canManagePermissions()) {
            $menuItems[] = [
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                          </svg>',
                'title' => '권한 관리',
                'url' => '/organizations/1/admin/permissions',
                'active' => request()->is('organizations/*/admin/permissions'),
                'description' => '역할 및 권한 설정'
            ];
        }

        // 결제 관리
        if ($permission->canManageBilling()) {
            $menuItems[] = [
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                          </svg>',
                'title' => '결제 관리',
                'url' => '/organizations/1/admin/billing',
                'active' => request()->is('organizations/*/admin/billing'),
                'description' => '요금제 및 결제 관리'
            ];
        }

        // 프로젝트 관리
        if ($permission->canManageProjects()) {
            $menuItems[] = [
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                          </svg>',
                'title' => '프로젝트 관리',
                'url' => '/organizations/1/admin/projects',
                'active' => request()->is('organizations/*/admin/projects'),
                'description' => '조직 프로젝트 관리'
            ];
        }

        return $menuItems;
    }
}