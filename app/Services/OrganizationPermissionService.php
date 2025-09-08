<?php

namespace App\Services;

use App\Models\User;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\ProjectMemberRole;
use App\Enums\ProjectRole;

/**
 * 새로운 역할 기반 권한 시스템 서비스
 * ProjectRole enum과 ProjectMemberRole 모델을 사용
 */
class OrganizationPermissionService
{
    /**
     * 사용자의 조직 내 최고 역할 가져오기
     */
    public static function getUserHighestRole(User $user, Organization $organization): ?ProjectRole
    {
        $member = OrganizationMember::where('user_id', $user->id)
            ->where('organization_id', $organization->id)
            ->first();

        if (!$member) {
            return null;
        }

        // 조직 내 프로젝트들에서 사용자의 역할 중 최고 권한 찾기
        $highestRole = null;
        $projects = $organization->projects;

        foreach ($projects as $project) {
            $memberRole = ProjectMemberRole::where('user_id', $user->id)
                ->where('project_id', $project->id)
                ->first();

            if ($memberRole) {
                $role = ProjectRole::from($memberRole->role);
                if (!$highestRole || $role->includes($highestRole)) {
                    $highestRole = $role;
                }
            }
        }

        return $highestRole ?? ProjectRole::GUEST;
    }

    /**
     * 사용자가 특정 역할 이상의 권한을 가지고 있는지 확인
     */
    public static function hasRole(User $user, Organization $organization, ProjectRole $requiredRole): bool
    {
        $userRole = self::getUserHighestRole($user, $organization);
        
        if (!$userRole) {
            return false;
        }

        return $userRole->includes($requiredRole);
    }

    /**
     * 멤버 관리 권한 확인
     */
    public static function canManageMembers(User $user, Organization $organization): bool
    {
        return self::hasRole($user, $organization, ProjectRole::ADMIN);
    }

    /**
     * 권한 관리 권한 확인
     */
    public static function canManagePermissions(User $user, Organization $organization): bool
    {
        return self::hasRole($user, $organization, ProjectRole::ADMIN);
    }

    /**
     * 결제 관리 권한 확인
     */
    public static function canManageBilling(User $user, Organization $organization): bool
    {
        return self::hasRole($user, $organization, ProjectRole::OWNER);
    }

    /**
     * 프로젝트 관리 권한 확인
     */
    public static function canManageProjects(User $user, Organization $organization): bool
    {
        return self::hasRole($user, $organization, ProjectRole::MODERATOR);
    }

    /**
     * 조직 삭제 권한 확인
     */
    public static function canDeleteOrganization(User $user, Organization $organization): bool
    {
        return self::hasRole($user, $organization, ProjectRole::OWNER);
    }

    /**
     * 역할의 표시 정보 가져오기
     */
    public static function getRoleDisplayInfo(ProjectRole $role): array
    {
        return [
            'label' => $role->getDisplayName(),
            'short_label' => $role->getDisplayName(),
            'description' => $role->getDescription(),
            'color' => $role->getColorClass(),
            'icon' => $role->getIcon(),
        ];
    }

    /**
     * 모든 역할을 계층 순서대로 가져오기
     */
    public static function getAllRolesInOrder(): array
    {
        return ProjectRole::getAllInOrder();
    }

    /**
     * 선택 옵션을 위한 역할 목록 가져오기
     */
    public static function getRoleSelectOptions(): array
    {
        $options = [];
        foreach (ProjectRole::getAllInOrder() as $role) {
            $options[$role->value] = $role->getDisplayName();
        }
        return $options;
    }

    /**
     * 사용자에게 역할 할당
     */
    public static function assignRole(User $user, int $projectId, ProjectRole $role): void
    {
        ProjectMemberRole::updateOrCreate(
            [
                'user_id' => $user->id,
                'project_id' => $projectId,
            ],
            [
                'role' => $role->value,
            ]
        );
    }

    /**
     * 사용자의 역할 제거
     */
    public static function removeRole(User $user, int $projectId): void
    {
        ProjectMemberRole::where('user_id', $user->id)
            ->where('project_id', $projectId)
            ->delete();
    }

    /**
     * 조직 멤버의 모든 역할 가져오기 (프로젝트별)
     */
    public static function getMemberRoles(User $user, Organization $organization): array
    {
        $roles = [];
        $projects = $organization->projects;

        foreach ($projects as $project) {
            $memberRole = ProjectMemberRole::where('user_id', $user->id)
                ->where('project_id', $project->id)
                ->first();

            if ($memberRole) {
                $roles[] = [
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'role' => ProjectRole::from($memberRole->role),
                ];
            }
        }

        return $roles;
    }
}