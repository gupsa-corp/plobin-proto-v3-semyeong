<?php

namespace App\Http\CoreApi\PlatformAdmin\Permissions\PermissionStats;

use App\Http\CoreApi\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Controller extends BaseController
{
    /**
     * 권한 관리 통계 정보를 반환합니다.
     */
    public function getStats(): JsonResponse
    {
        try {
            // 총 권한 수
            $totalPermissions = Permission::whereIn('guard_name', ['web', 'platform'])->count();
            
            // 총 역할 수
            $totalRoles = Role::whereIn('guard_name', ['web', 'platform'])->count();
            
            // 활성 역할 수 (권한이 할당된 역할)
            $activeRoles = Role::whereIn('guard_name', ['web', 'platform'])
                ->whereHas('permissions')
                ->count();
            
            // 카테고리별 권한 분포
            $permissionsByCategory = Permission::whereIn('guard_name', ['web', 'platform'])
                ->selectRaw('COALESCE(category, "기타") as category, COUNT(*) as count')
                ->groupBy('category')
                ->orderBy('category')
                ->get()
                ->pluck('count', 'category')
                ->toArray();
            
            // 역할별 권한 수
            $rolePermissionCounts = Role::whereIn('guard_name', ['web', 'platform'])
                ->withCount('permissions')
                ->get()
                ->map(function($role) {
                    return [
                        'name' => $role->name,
                        'permissions_count' => $role->permissions_count
                    ];
                })
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
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
}