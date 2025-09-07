<?php

namespace App\Services;

use App\Models\DynamicPermissionRule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DynamicPermissionService
{
    protected int $cacheMinutes = 60; // 캐시 시간 (분)

    /**
     * 사용자가 특정 리소스에 대한 액션을 수행할 수 있는지 확인
     */
    public function canPerformAction($user, string $resourceType, string $action, array $context = []): bool
    {
        // 1. 캐시에서 규칙 조회
        $cacheKey = "permission_rule_{$resourceType}_{$action}";
        $rule = Cache::remember($cacheKey, $this->cacheMinutes * 60, function () use ($resourceType, $action) {
            return DynamicPermissionRule::active()
                ->forResource($resourceType)
                ->forAction($action)
                ->first();
        });

        if (!$rule) {
            // 규칙이 없으면 기본적으로 거부
            Log::warning('No permission rule found', [
                'resource_type' => $resourceType,
                'action' => $action,
                'user_id' => $user?->id ?? 'guest'
            ]);
            return false;
        }

        // 2. 권한 규칙 실행
        return $rule->checkPermission($user, $context);
    }

    /**
     * 게스트(비로그인) 사용자가 공개 액션을 수행할 수 있는지 확인
     */
    public function canPerformPublicAction(string $action, array $context = []): bool
    {
        return $this->canPerformAction(null, 'public_access', $action, $context);
    }

    /**
     * 사용자에게 기본 역할과 권한을 할당 (기존 enum 시스템과의 호환성)
     */
    public function assignBasicPermissions($user, int $permissionLevel)
    {
        // 기존 enum 값을 새로운 역할 시스템으로 매핑
        $roleMapping = [
            0 => [], // INVITED - 권한 없음
            100 => ['user'], // USER
            150 => ['user', 'advanced_user'], // USER_ADVANCED  
            200 => ['user', 'service_manager'], // SERVICE_MANAGER
            250 => ['user', 'service_manager', 'senior_service_manager'], // SERVICE_MANAGER_SENIOR
            300 => ['user', 'service_manager', 'organization_admin'], // ORGANIZATION_ADMIN
            350 => ['user', 'service_manager', 'organization_admin', 'senior_organization_admin'], // ORGANIZATION_ADMIN_SENIOR
            400 => ['user', 'service_manager', 'organization_admin', 'organization_owner'], // ORGANIZATION_OWNER
            450 => ['user', 'service_manager', 'organization_admin', 'organization_owner', 'organization_founder'], // ORGANIZATION_OWNER_FOUNDER
            500 => ['platform_admin'], // PLATFORM_ADMIN
            550 => ['platform_admin', 'super_admin'], // PLATFORM_ADMIN_SUPER
        ];

        $roles = $roleMapping[$permissionLevel] ?? [];
        
        if (!empty($roles)) {
            $user->syncRoles($roles);
        }

        return $roles;
    }

    /**
     * 기존 PermissionService::canPerformAction과 호환되는 래퍼 메소드
     */
    public function canPerformLegacyAction($userPermission, string $category, string $action): bool
    {
        // 임시 사용자 객체 생성 (기존 코드와의 호환성)
        $user = new \App\Models\User();
        $user->permission_level = $userPermission->value ?? $userPermission;

        return $this->canPerformAction($user, $category, $action);
    }

    /**
     * 권한 규칙 캐시 클리어
     */
    public function clearCache(): void
    {
        Cache::tags(['permission_rules'])->flush();
    }

    /**
     * 사용자별 권한 요약 정보 반환
     */
    public function getUserPermissionSummary($user): array
    {
        return [
            'roles' => $user->getRoleNames()->toArray(),
            'permissions' => $user->getPermissionNames()->toArray(),
            'all_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            'legacy_level' => $this->getUserLegacyLevel($user),
        ];
    }

    /**
     * 기존 시스템과의 호환성을 위한 레벨 계산
     */
    private function getUserLegacyLevel($user): int
    {
        // 조직별 권한 레벨 계산
        $organizationMember = $user->organizationMemberships()
            ->where('organization_id', request()->route('organization'))
            ->first();

        if ($organizationMember) {
            return $organizationMember->permission_level;
        }

        // 역할 기반 레벨 계산 (대략적)
        if ($user->hasRole('super_admin')) return 550;
        if ($user->hasRole('platform_admin')) return 500;
        if ($user->hasRole('organization_founder')) return 450;
        if ($user->hasRole('organization_owner')) return 400;
        if ($user->hasRole('senior_organization_admin')) return 350;
        if ($user->hasRole('organization_admin')) return 300;
        if ($user->hasRole('senior_service_manager')) return 250;
        if ($user->hasRole('service_manager')) return 200;
        if ($user->hasRole('advanced_user')) return 150;
        if ($user->hasRole('user')) return 100;

        return 0;
    }

    /**
     * 권한 규칙 생성 도우미
     */
    public function createRule(array $data): DynamicPermissionRule
    {
        $rule = DynamicPermissionRule::create($data);
        
        // 캐시 클리어
        $this->clearCache();
        
        Log::info('Permission rule created', [
            'rule_id' => $rule->id,
            'resource_type' => $rule->resource_type,
            'action' => $rule->action
        ]);

        return $rule;
    }

    /**
     * 권한 규칙 업데이트 도우미
     */
    public function updateRule(DynamicPermissionRule $rule, array $data): bool
    {
        $updated = $rule->update($data);
        
        if ($updated) {
            // 캐시 클리어
            $this->clearCache();
            
            Log::info('Permission rule updated', [
                'rule_id' => $rule->id,
                'resource_type' => $rule->resource_type,
                'action' => $rule->action
            ]);
        }

        return $updated;
    }

    /**
     * 권한 규칙 삭제 도우미
     */
    public function deleteRule(DynamicPermissionRule $rule): bool
    {
        $deleted = $rule->delete();
        
        if ($deleted) {
            // 캐시 클리어
            $this->clearCache();
            
            Log::info('Permission rule deleted', [
                'rule_id' => $rule->id,
                'resource_type' => $rule->resource_type,
                'action' => $rule->action
            ]);
        }

        return $deleted;
    }

    /**
     * 권한 매트릭스 반환
     */
    public function getPermissionMatrix(): array
    {
        $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
        $permissions = \Spatie\Permission\Models\Permission::all();
        
        return $roles->map(function ($role) use ($permissions) {
            $rolePermissions = $role->permissions->pluck('name')->toArray();
            
            return [
                'role' => $role,
                'permissions' => $permissions->map(function ($permission) use ($rolePermissions) {
                    return [
                        'permission' => $permission,
                        'has_permission' => in_array($permission->name, $rolePermissions)
                    ];
                })->toArray()
            ];
        })->toArray();
    }

    /**
     * 역할의 사용 가능한 기능 목록 반환
     */
    public function getRoleFeatures(string $roleName): array
    {
        $role = \Spatie\Permission\Models\Role::findByName($roleName);
        if (!$role) {
            return [];
        }

        return $role->permissions->map(function ($permission) {
            return [
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'category' => $this->getPermissionCategory($permission->name)
            ];
        })->groupBy('category')->toArray();
    }

    /**
     * 권한의 사용 가능한 기능 목록 반환
     */
    public function getPermissionFeatures(string $permissionName): array
    {
        $permission = \Spatie\Permission\Models\Permission::findByName($permissionName);
        if (!$permission) {
            return [];
        }

        return $permission->roles->map(function ($role) {
            return [
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'users_count' => $role->users()->count()
            ];
        })->toArray();
    }

    /**
     * 권한 카테고리 분류
     */
    private function getPermissionCategory(string $permissionName): string
    {
        if (str_contains($permissionName, 'member')) return '멤버 관리';
        if (str_contains($permissionName, 'project')) return '프로젝트 관리';
        if (str_contains($permissionName, 'billing')) return '결제 관리';
        if (str_contains($permissionName, 'organization')) return '조직 설정';
        if (str_contains($permissionName, 'permission')) return '권한 관리';
        return '기타';
    }
}