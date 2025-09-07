<?php

namespace App\Http\CoreApi\PlatformAdmin\Permissions\ExportPermissions;

use App\Http\CoreApi\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Controller extends BaseController
{
    /**
     * 권한 데이터를 내보냅니다.
     */
    public function export(): JsonResponse
    {
        try {
            // 모든 권한 조회
            $permissions = Permission::whereIn('guard_name', ['web', 'platform'])
                ->with(['roles'])
                ->orderBy('category')
                ->orderBy('name')
                ->get();

            // 모든 역할 조회
            $roles = Role::whereIn('guard_name', ['web', 'platform'])
                ->with(['permissions'])
                ->orderBy('name')
                ->get();

            // 권한 데이터 구조화
            $permissionsData = [];
            $categoriesData = [];
            
            foreach ($permissions as $permission) {
                $category = $permission->category ?: '기타';
                
                if (!isset($categoriesData[$category])) {
                    $categoriesData[$category] = [];
                }
                
                $permissionData = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'description' => $permission->description,
                    'guard_name' => $permission->guard_name,
                    'category' => $category,
                    'roles' => $permission->roles->pluck('name')->toArray(),
                    'is_active' => $permission->is_active ?? true,
                    'created_at' => $permission->created_at ? $permission->created_at->toISOString() : null,
                    'updated_at' => $permission->updated_at ? $permission->updated_at->toISOString() : null
                ];
                
                $permissionsData[] = $permissionData;
                $categoriesData[$category][] = $permissionData;
            }

            // 역할 데이터 구조화
            $rolesData = [];
            foreach ($roles as $role) {
                $rolesData[] = [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'permissions' => $role->permissions->pluck('name')->toArray(),
                    'permissions_count' => $role->permissions->count(),
                    'created_at' => $role->created_at ? $role->created_at->toISOString() : null,
                    'updated_at' => $role->updated_at ? $role->updated_at->toISOString() : null
                ];
            }

            // 내보내기 데이터
            $exportData = [
                'export_date' => now()->toISOString(),
                'permissions' => $permissionsData,
                'roles' => $rolesData,
                'categories' => $categoriesData,
                'statistics' => [
                    'total_permissions' => count($permissionsData),
                    'total_roles' => count($rolesData),
                    'total_categories' => count($categoriesData),
                    'permissions_by_category' => array_map('count', $categoriesData)
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => '권한 데이터 내보내기가 완료되었습니다.',
                'data' => $exportData
            ]);

        } catch (\Exception $e) {
            \Log::error('Permission export error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '권한 데이터 내보내기 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}