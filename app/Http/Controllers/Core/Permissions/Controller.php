<?php

namespace App\Http\Controllers\Core\Permissions;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class Controller extends BaseController
{
    /**
     * 계층적 역할 정보를 반환합니다.
     */
    public function getRoles(): JsonResponse
    {
        try {
            // 모든 역할을 계층 정보와 함께 조회
            $roles = Role::whereIn('guard_name', ['web', 'platform'])
                ->with(['permissions'])
                ->orderBy('scope_level')
                ->orderBy('name')
                ->get();

            $rolesData = [];
            foreach ($roles as $role) {
                $rolesData[] = [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'description' => $role->description ?? '',
                    'scope_level' => $this->getScopeLevel($role),
                    'hierarchy_path' => $this->getHierarchyPath($role),
                    'is_active' => $role->is_active ?? true,
                    'permissions' => $role->permissions->map(function($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                            'category' => $permission->category ?? '기타'
                        ];
                    })->toArray(),
                    'permissions_count' => $role->permissions->count(),
                    'users_count' => $this->getUsersCount($role),
                    'children_count' => $this->getChildrenCount($role),
                    'created_at' => $role->created_at ? $role->created_at->toISOString() : null,
                    'updated_at' => $role->updated_at ? $role->updated_at->toISOString() : null,
                    'creator' => $this->getCreatorInfo($role),
                    'parent_role' => $this->getParentRoleInfo($role),
                    'organization' => $this->getOrganizationInfo($role)
                ];
            }

            // 범위별 통계
            $scopeStatistics = $this->getScopeStatistics($roles);

            return response()->json([
                'success' => true,
                'data' => [
                    'roles' => $rolesData,
                    'scope_statistics' => $scopeStatistics,
                    'total_roles' => count($rolesData),
                    'active_roles' => collect($rolesData)->where('is_active', true)->count()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Core permissions getRoles error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => '역할 정보 로드 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 역할의 범위 레벨을 결정합니다.
     */
    private function getScopeLevel($role): string
    {
        // 역할 이름을 기반으로 범위 레벨 결정
        $name = strtolower($role->name);

        if (str_contains($name, 'platform') || str_contains($name, 'super') || str_contains($name, 'system')) {
            return 'platform';
        } elseif (str_contains($name, 'organization') || str_contains($name, 'org') || str_contains($name, 'company')) {
            return 'organization';
        } elseif (str_contains($name, 'project') || str_contains($name, 'proj')) {
            return 'project';
        } elseif (str_contains($name, 'page')) {
            return 'page';
        }

        // 기본값: organization
        return 'organization';
    }

    /**
     * 역할의 계층 경로를 생성합니다.
     */
    private function getHierarchyPath($role): string
    {
        $scopeLevel = $this->getScopeLevel($role);

        switch ($scopeLevel) {
            case 'platform':
                return '플랫폼';
            case 'organization':
                return '플랫폼 > 조직';
            case 'project':
                return '플랫폼 > 조직 > 프로젝트';
            case 'page':
                return '플랫폼 > 조직 > 프로젝트 > 페이지';
            default:
                return '플랫폼 > 조직';
        }
    }

    /**
     * 역할에 할당된 사용자 수를 계산합니다.
     */
    private function getUsersCount($role): int
    {
        try {
            return User::role($role->name)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 하위 역할 수를 계산합니다.
     */
    private function getChildrenCount($role): int
    {
        // 현재는 단순히 0을 반환 (향후 계층 구조 구현시 수정)
        return 0;
    }

    /**
     * 역할 생성자 정보를 반환합니다.
     */
    private function getCreatorInfo($role): ?array
    {
        // 생성자 정보가 있다면 반환
        if (isset($role->creator_id)) {
            $creator = User::find($role->creator_id);
            if ($creator) {
                return [
                    'id' => $creator->id,
                    'name' => $creator->name,
                    'email' => $creator->email
                ];
            }
        }

        return null;
    }

    /**
     * 부모 역할 정보를 반환합니다.
     */
    private function getParentRoleInfo($role): ?array
    {
        // 현재는 null 반환 (향후 계층 구조 구현시 수정)
        return null;
    }

    /**
     * 조직 정보를 반환합니다.
     */
    private function getOrganizationInfo($role): ?array
    {
        // 조직 관련 역할일 경우 조직 정보 반환
        if (isset($role->organization_id)) {
            $organization = \App\Models\Organization::find($role->organization_id);
            if ($organization) {
                return [
                    'id' => $organization->id,
                    'name' => $organization->name
                ];
            }
        }

        return null;
    }

    /**
     * 범위별 통계를 생성합니다.
     */
    private function getScopeStatistics($roles): array
    {
        $statistics = [];

        foreach ($roles as $role) {
            $scopeLevel = $this->getScopeLevel($role);

            if (!isset($statistics[$scopeLevel])) {
                $statistics[$scopeLevel] = [
                    'count' => 0,
                    'active_count' => 0,
                    'total_permissions' => 0
                ];
            }

            $statistics[$scopeLevel]['count']++;
            if ($role->is_active ?? true) {
                $statistics[$scopeLevel]['active_count']++;
            }
            $statistics[$scopeLevel]['total_permissions'] += $role->permissions->count();
        }

        return $statistics;
    }
}
