<?php

namespace App\Http\Controllers\PlatformAdmin\Permissions\DeleteRole;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;

class Controller extends BaseController
{
    /**
     * 역할을 삭제합니다.
     */
    public function delete(Request $request, $id): JsonResponse
    {
        try {
            // 역할 찾기
            $role = Role::find($id);
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => '해당 역할을 찾을 수 없습니다.'
                ], 404);
            }

            // 하위 역할 체크 (자식 역할이 있는지 확인)
            if (method_exists($role, 'children') && $role->children()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => '하위 역할이 존재하여 삭제할 수 없습니다. 먼저 하위 역할을 삭제해주세요.'
                ], 422);
            }

            // 시스템 역할 보호 (기본 역할 삭제 방지)
            $protectedRoles = ['platform_admin', 'super_admin', 'admin'];
            if (in_array($role->name, $protectedRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => '시스템 기본 역할은 삭제할 수 없습니다.'
                ], 422);
            }

            // 사용자가 할당된 역할인지 확인
            $usersCount = User::role($role->name)->count();
            if ($usersCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "이 역할이 할당된 사용자가 {$usersCount}명 있습니다. 먼저 사용자의 역할을 변경해주세요."
                ], 422);
            }

            $roleName = $role->name;

            // 역할 삭제 (권한은 자동으로 해제됨)
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => "역할 '{$roleName}'이(가) 성공적으로 삭제되었습니다."
            ]);

        } catch (\Exception $e) {
            \Log::error('Role deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => '역할 삭제 중 오류가 발생했습니다.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
