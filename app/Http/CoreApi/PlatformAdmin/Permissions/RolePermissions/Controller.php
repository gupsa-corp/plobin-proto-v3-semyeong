<?php

namespace App\Http\CoreApi\PlatformAdmin\Permissions\RolePermissions;

use App\Http\CoreApi\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Controller extends BaseController
{
    /**
     * 역할의 권한을 업데이트합니다.
     */
    public function updateRolePermissions(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'role_name' => 'required|string',
                'permissions' => 'required|array',
                'permissions.*' => 'string|exists:permissions,name'
            ]);

            $roleName = $request->input('role_name');
            $permissionNames = $request->input('permissions');

            // 역할 찾기
            $role = Role::findByName($roleName);
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => '해당 역할을 찾을 수 없습니다.'
                ], 404);
            }

            // 권한 유효성 검사
            $validPermissions = Permission::whereIn('name', $permissionNames)
                ->whereIn('guard_name', ['web', 'platform'])
                ->pluck('name')
                ->toArray();

            $invalidPermissions = array_diff($permissionNames, $validPermissions);
            if (!empty($invalidPermissions)) {
                return response()->json([
                    'success' => false,
                    'message' => '유효하지 않은 권한이 있습니다: ' . implode(', ', $invalidPermissions)
                ], 400);
            }

            // 기존 권한 제거 후 새 권한 동기화
            $role->syncPermissions($validPermissions);

            return response()->json([
                'success' => true,
                'message' => '역할 권한이 성공적으로 업데이트되었습니다.',
                'data' => [
                    'role_name' => $roleName,
                    'permissions_count' => count($validPermissions),
                    'permissions' => $validPermissions
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => '입력 데이터가 유효하지 않습니다.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Role permissions update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '역할 권한 업데이트 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}