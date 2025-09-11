<?php

namespace App\Http\Controllers\PlatformAdmin\Permissions\PermissionMatrix;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Controller extends BaseController
{
    /**
     * 권한 매트릭스 데이터를 반환합니다.
     */
    public function getMatrix(Request $request): JsonResponse
    {
        try {
            $scope = $request->get('scope', 'platform');
            $organizationId = $request->get('organization_id');

            // 스코프에 따라 가드명 결정 - 모든 권한이 'web' 가드를 사용
            $guardNames = ['web'];

            // 역할 조회 - 스코프에 따라 필터링
            $roleQuery = Role::whereIn('guard_name', $guardNames)->orderBy('name');

            if ($scope === 'organization' && $organizationId) {
                // 조직별 역할만 조회 (조직 ID가 있는 경우)
                $roleQuery->where(function($query) use ($organizationId) {
                    $query->where('organization_id', $organizationId)
                          ->orWhereNull('organization_id'); // 공통 역할도 포함
                });
            } elseif ($scope === 'platform') {
                // 플랫폼 역할만 조회
                $roleQuery->whereNull('organization_id');
            }

            $roles = $roleQuery->get()->pluck('name')->toArray();

            // 권한 조회 - 모든 권한을 가져옴 (scope_level 필터링 제거)
            $permissionQuery = Permission::whereIn('guard_name', $guardNames)
                ->orderBy('name');

            $permissions = $permissionQuery->get();

            $permissionsData = [];
            foreach ($permissions as $permission) {
                // 권한 이름을 기반으로 카테고리 자동 분류
                $category = $this->getCategoryFromPermissionName($permission->name);
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

    /**
     * 권한 이름을 기반으로 카테고리를 자동 분류합니다.
     */
    private function getCategoryFromPermissionName(string $permissionName): string
    {
        if (strpos($permissionName, 'member') !== false) {
            return '회원 관리';
        }

        if (strpos($permissionName, 'project') !== false) {
            return '프로젝트 관리';
        }

        if (strpos($permissionName, 'page') !== false) {
            return '페이지 관리';
        }

        if (strpos($permissionName, 'billing') !== false || strpos($permissionName, 'subscription') !== false || strpos($permissionName, 'receipt') !== false) {
            return '결제 관리';
        }

        if (strpos($permissionName, 'organization') !== false) {
            return '조직 설정';
        }

        if (strpos($permissionName, 'permission') !== false || strpos($permissionName, 'role') !== false) {
            return '권한 관리';
        }

        if (strpos($permissionName, 'admin') !== false || strpos($permissionName, 'system') !== false) {
            return '시스템 관리';
        }

        if (strpos($permissionName, 'public') !== false) {
            return '공개 접근';
        }

        return '기타';
    }
}
