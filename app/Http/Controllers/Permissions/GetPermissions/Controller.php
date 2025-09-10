<?php

namespace App\Http\Controllers\Permissions\GetPermissions;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class Controller extends BaseController
{
    /**
     * Get all permissions with categories and hierarchical roles
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $permissions = Permission::all()->groupBy('category');

            // 계층별로 역할 정보를 가져오기
            $roles = Role::with([
                'permissions',
                'parentRole',
                'childRoles',
                'creator',
                'organization'
            ])->get();

            // 계층별로 역할을 그룹화
            $rolesByScope = $roles->groupBy('scope_level');

            // 각 역할에 추가 정보를 포함
            $enrichedRoles = $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'scope_level' => $role->scope_level,
                    'organization_id' => $role->organization_id,
                    'project_id' => $role->project_id,
                    'page_id' => $role->page_id,
                    'parent_role_id' => $role->parent_role_id,
                    'description' => $role->description,
                    'is_active' => $role->is_active,
                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,

                    // 관계 정보
                    'organization' => $role->organization,
                    'parent_role' => $role->parentRole ? [
                        'id' => $role->parentRole->id,
                        'name' => $role->parentRole->name,
                        'scope_level' => $role->parentRole->scope_level,
                    ] : null,
                    'creator' => $role->creator ? [
                        'id' => $role->creator->id,
                        'name' => $role->creator->name,
                        'email' => $role->creator->email,
                    ] : null,

                    // 계층 정보
                    'hierarchy_path' => $role->getHierarchyPath(),
                    'inheritance_chain' => collect($role->getInheritanceChain())->map(function ($r) {
                        return [
                            'id' => $r->id,
                            'name' => $r->name,
                            'scope_level' => $r->scope_level,
                        ];
                    }),

                    // 권한 정보
                    'permissions' => $role->permissions->map(function ($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                            'category' => $permission->category ?? 'uncategorized',
                        ];
                    }),

                    // 자식 역할 수
                    'children_count' => $role->childRoles->count(),
                ];
            });

            // 범위별 통계
            $scopeStats = [
                'platform' => $rolesByScope->get('platform', collect())->count(),
                'organization' => $rolesByScope->get('organization', collect())->count(),
                'project' => $rolesByScope->get('project', collect())->count(),
                'page' => $rolesByScope->get('page', collect())->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'permissions' => $permissions,
                    'roles' => $enrichedRoles,
                    'roles_by_scope' => $rolesByScope,
                    'scope_statistics' => $scopeStats,
                    'total_roles' => $roles->count(),
                    'active_roles' => $roles->where('is_active', true)->count(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch permissions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load permissions data'
            ], 500);
        }
    }
}
