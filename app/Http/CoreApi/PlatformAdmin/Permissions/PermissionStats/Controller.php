<?php

namespace App\Http\CoreApi\PlatformAdmin\Permissions\PermissionStats;

use App\Http\CoreApi\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Controller extends BaseController
{
    /**
     * 권한 관리 통계 정보를 반환합니다.
     */
    public function getStats(Request $request): JsonResponse
    {
        try {
            $scope = $request->get('scope', 'platform');
            $organizationId = $request->get('organization_id');

            // 스코프에 따라 가드명 결정
            $guardNames = $scope === 'platform' ? ['platform', 'web'] : ['web'];

            // 권한 쿼리
            $permissionQuery = Permission::whereIn('guard_name', $guardNames);
            if ($scope === 'organization') {
                $permissionQuery->where(function($query) {
                    $query->where('scope_level', 'organization')
                          ->orWhere('scope_level', 'project')
                          ->orWhere('scope_level', 'page')
                          ->orWhereNull('scope_level');
                });
            } elseif ($scope === 'platform') {
                $permissionQuery->where(function($query) {
                    $query->where('scope_level', 'platform')
                          ->orWhereNull('scope_level');
                });
            }

            // 역할 쿼리
            $roleQuery = Role::whereIn('guard_name', $guardNames);
            if ($scope === 'organization' && $organizationId) {
                $roleQuery->where(function($query) use ($organizationId) {
                    $query->where('organization_id', $organizationId)
                          ->orWhereNull('organization_id');
                });
            } elseif ($scope === 'platform') {
                $roleQuery->whereNull('organization_id');
            }

            // 통계 계산
            $totalPermissions = $permissionQuery->count();
            $totalRoles = $roleQuery->count();
            
            // 활성 역할 수 (권한이 할당된 역할) - 스코프 적용
            $activeRolesQuery = clone $roleQuery;
            $activeRoles = $activeRolesQuery->whereHas('permissions')->count();
            
            // 카테고리별 권한 분포 - 스코프 적용
            $permissionsByCategoryQuery = clone $permissionQuery;
            $permissionsByCategory = $permissionsByCategoryQuery
                ->selectRaw('COALESCE(category, "기타") as category, COUNT(*) as count')
                ->groupBy('category')
                ->orderBy('category')
                ->get()
                ->pluck('count', 'category')
                ->toArray();
            
            // 역할별 권한 수 - 스코프 적용
            $rolePermissionCountsQuery = clone $roleQuery;
            $rolePermissionCounts = $rolePermissionCountsQuery
                ->withCount('permissions')
                ->get()
                ->map(function($role) {
                    return [
                        'name' => $role->name,
                        'permissions_count' => $role->permissions_count,
                        'scope' => $this->determineRoleScope($role)
                    ];
                })
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'scope' => $scope,
                    'organization_id' => $organizationId,
                    'total_permissions' => $totalPermissions,
                    'total_roles' => $totalRoles,
                    'active_roles' => $activeRoles,
                    'permissions_by_category' => $permissionsByCategory,
                    'role_permission_counts' => $rolePermissionCounts,
                    'categories_count' => count($permissionsByCategory)
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Permission stats error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '권한 통계 로드 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 역할의 스코프를 결정합니다.
     */
    private function determineRoleScope($role): string
    {
        if ($role->guard_name === 'platform' || $role->organization_id === null) {
            return 'platform';
        }
        
        return 'organization';
    }
}