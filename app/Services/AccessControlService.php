<?php

namespace App\Services;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectPage;
use App\Models\ProjectMemberRole;
use App\Models\OrganizationMember;
use App\Enums\ProjectRole;
use App\Enums\PageAccessLevel;
use Spatie\Permission\Models\Role;

class AccessControlService
{
    /**
     * 역할별 접근 가능한 페이지 레벨 정의
     */
    private const ROLE_HIERARCHY = [
        'guest' => ['public'],
        'member' => ['public', 'member'],
        'contributor' => ['public', 'member', 'contributor'],
        'moderator' => ['public', 'member', 'contributor', 'moderator'],
        'admin' => ['public', 'member', 'contributor', 'moderator', 'admin'],
        'owner' => ['public', 'member', 'contributor', 'moderator', 'admin', 'owner'],
    ];

    /**
     * 사용자가 페이지에 접근할 수 있는지 확인
     */
    public function canUserAccessPage(User $user, ProjectPage $page): bool
    {
        $project = $page->project;
        
        // 프로젝트 소유자는 항상 접근 가능
        if ($project->user_id === $user->id) {
            return true;
        }

        // 사용자의 프로젝트 내 역할 확인
        $userRole = $this->getUserProjectRole($user, $project);
        
        // 페이지의 접근 레벨 확인
        $pageAccessLevel = $page->getEffectiveAccessLevel();
        
        // 커스텀 역할인 경우
        if ($pageAccessLevel === PageAccessLevel::CUSTOM) {
            return $this->checkCustomRoleAccess($user, $page);
        }
        
        // 표준 역할 기반 접근 확인
        return $pageAccessLevel->canRoleAccess($userRole);
    }

    /**
     * 사용자의 프로젝트 내 역할 조회
     */
    public function getUserProjectRole(User $user, Project $project): ProjectRole
    {
        // 프로젝트 소유자 확인
        if ($project->user_id === $user->id) {
            return ProjectRole::OWNER;
        }

        // 프로젝트별 역할 확인
        $projectRole = ProjectMemberRole::where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->first();

        if ($projectRole) {
            return ProjectRole::from($projectRole->role);
        }

        // 조직 멤버십을 통한 기본 역할 확인
        $organizationMember = OrganizationMember::where('organization_id', $project->organization_id)
            ->where('user_id', $user->id)
            ->where('invitation_status', 'accepted')
            ->first();

        if ($organizationMember) {
            // 조직 역할을 프로젝트 역할로 매핑
            return $this->mapOrganizationRoleToProjectRole($organizationMember->role);
        }

        // 기본값은 게스트
        return ProjectRole::GUEST;
    }

    /**
     * 조직 역할을 프로젝트 역할로 매핑
     */
    private function mapOrganizationRoleToProjectRole(?string $organizationRole): ProjectRole
    {
        if (!$organizationRole) {
            return ProjectRole::GUEST;
        }

        // 조직 역할을 프로젝트 역할로 매핑
        return match($organizationRole) {
            'admin', 'owner' => ProjectRole::ADMIN,
            'moderator' => ProjectRole::MODERATOR,
            'contributor' => ProjectRole::CONTRIBUTOR,  
            'member' => ProjectRole::MEMBER,
            default => ProjectRole::GUEST,
        };
    }

    /**
     * 커스텀 역할 접근 권한 확인
     */
    private function checkCustomRoleAccess(User $user, ProjectPage $page): bool
    {
        $allowedRoles = $page->allowed_roles ?? [];
        
        if (empty($allowedRoles)) {
            return false;
        }

        // 프로젝트 매니저(PM)인지 확인
        if ($page->project->user_id === $user->id) {
            return true;
        }

        foreach ($allowedRoles as $allowedItem) {
            // 이메일 주소로 체크
            if (filter_var($allowedItem, FILTER_VALIDATE_EMAIL)) {
                if ($user->email === $allowedItem) {
                    return true;
                }
            }
            // 사용자 ID로 체크  
            elseif (is_numeric($allowedItem)) {
                if ($user->id == $allowedItem) {
                    return true;
                }
            }
        }

        // 기존 커스텀 역할 확인 (하위 호환성)
        $userCustomRoles = $user->roles()
            ->where('name', 'like', 'project_' . $page->project_id . '_%')
            ->pluck('id')
            ->toArray();

        return !empty(array_intersect($userCustomRoles, $allowedRoles));
    }

    /**
     * 사용자가 프로젝트에 접근할 수 있는지 확인
     */
    public function canUserAccessProject(User $user, Project $project): bool
    {
        // 프로젝트 소유자는 항상 접근 가능
        if ($project->user_id === $user->id) {
            return true;
        }

        // 사용자의 프로젝트 내 역할 확인
        $userRole = $this->getUserProjectRole($user, $project);
        
        // 프로젝트 기본 접근 레벨 확인
        $projectAccessLevel = PageAccessLevel::from($project->default_access_level ?? 'member');
        
        return $projectAccessLevel->canRoleAccess($userRole);
    }

    /**
     * 사용자에게 프로젝트 내 역할 할당
     */
    public function assignUserRole(User $user, Project $project, ProjectRole $role): void
    {
        ProjectMemberRole::updateOrCreate(
            [
                'project_id' => $project->id,
                'user_id' => $user->id,
            ],
            [
                'role' => $role->value,
            ]
        );
    }

    /**
     * 사용자의 프로젝트 내 역할 제거
     */
    public function removeUserRole(User $user, Project $project): void
    {
        ProjectMemberRole::where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->delete();
    }

    /**
     * 프로젝트의 모든 멤버와 역할 조회
     */
    public function getProjectMembers(Project $project): array
    {
        $members = [];
        
        // 조직 멤버들 조회
        $organizationMembers = OrganizationMember::with('user')
            ->where('organization_id', $project->organization_id)
            ->where('invitation_status', 'accepted')
            ->get();

        foreach ($organizationMembers as $orgMember) {
            $projectRole = $this->getUserProjectRole($orgMember->user, $project);
            
            $members[] = [
                'user' => $orgMember->user,
                'role' => $projectRole,
                'is_owner' => $project->user_id === $orgMember->user->id,
                'organization_role' => $orgMember->role,
            ];
        }

        return $members;
    }

    /**
     * 페이지에 접근 가능한 역할 목록 조회
     */
    public function getPageAccessibleRoles(ProjectPage $page): array
    {
        $accessLevel = $page->getEffectiveAccessLevel();
        
        if ($accessLevel === PageAccessLevel::CUSTOM) {
            return $page->allowed_roles ?? [];
        }

        $roles = [];
        foreach (ProjectRole::getAllInOrder() as $role) {
            if ($accessLevel->canRoleAccess($role)) {
                $roles[] = $role->value;
            }
        }

        return $roles;
    }

    /**
     * 프로젝트의 페이지별 접근 권한 요약 조회
     */
    public function getProjectPagesAccessSummary(Project $project): array
    {
        $pages = $project->pages()->get();
        $summary = [];

        foreach ($pages as $page) {
            $accessLevel = $page->getEffectiveAccessLevel();
            $accessibleRoles = $this->getPageAccessibleRoles($page);

            $summary[] = [
                'page' => $page,
                'access_level' => $accessLevel,
                'accessible_roles' => $accessibleRoles,
                'is_restricted' => $accessLevel !== PageAccessLevel::PUBLIC,
            ];
        }

        return $summary;
    }
}