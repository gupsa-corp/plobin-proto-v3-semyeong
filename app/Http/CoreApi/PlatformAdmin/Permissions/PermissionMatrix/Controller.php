<?php

namespace App\Http\CoreApi\PlatformAdmin\Permissions\PermissionMatrix;

use App\Http\CoreApi\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Controller extends BaseController
{
    /**
     * 권한 매트릭스 데이터를 반환합니다.
     */
    public function getMatrix(): JsonResponse
    {
        try {
            // 모든 역할 조회
            $roles = Role::whereIn('guard_name', ['web', 'platform'])
                ->orderBy('name')
                ->get()
                ->pluck('name')
                ->toArray();

            // 모든 권한을 카테고리별로 분류
            $permissions = Permission::whereIn('guard_name', ['web', 'platform'])
                ->orderBy('category')
                ->orderBy('name')
                ->get();

            $permissionsData = [];
            foreach ($permissions as $permission) {
                $category = $permission->category ?: '기타';
                if (!isset($permissionsData[$category])) {
                    $permissionsData[$category] = [];
                }
                
                $permissionsData[$category][] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'description' => $permission->description ?: '',
                    'guard_name' => $permission->guard_name,
                ];
            }

            // 권한 매트릭스 생성 (역할별 권한 보유 상태)
            $matrix = [];
            foreach ($roles as $role) {
                $roleModel = Role::findByName($role);
                $rolePermissions = $roleModel->permissions->pluck('name')->toArray();
                
                $matrix[$role] = [];
                foreach ($permissionsData as $category => $categoryPermissions) {
                    $matrix[$role][$category] = [];
                    foreach ($categoryPermissions as $permission) {
                        $matrix[$role][$category][$permission['name']] = in_array($permission['name'], $rolePermissions);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'roles' => $roles,
                    'permissions' => $permissionsData,
                    'matrix' => $matrix
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Permission matrix error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '권한 매트릭스 로드 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}