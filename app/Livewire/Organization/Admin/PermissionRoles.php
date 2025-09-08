<?php

namespace App\Livewire\Organization\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Organization;
use App\Services\DynamicPermissionService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;

class PermissionRoles extends Component
{
    public $organizationId;
    public $organization;

    public function mount($organizationId = 1)
    {
        $this->organizationId = $organizationId;
        $this->loadData();
    }

    public function loadData()
    {
        $this->organization = Organization::with(['users.roles', 'users.permissions'])->find($this->organizationId);
    }

    /**
     * 새 역할 생성
     */
    public function createRole($roleName, $roleDescription, $permissions = [])
    {
        try {
            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);

            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }

            $this->loadData();
            
            session()->flash('success', '새 역할이 성공적으로 생성되었습니다.');
        } catch (\Exception $e) {
            session()->flash('error', '역할 생성 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    /**
     * 역할 권한 업데이트
     */
    public function updateRolePermissions($roleId, $permissions)
    {
        try {
            $role = Role::find($roleId);
            if (!$role) {
                throw new \Exception('역할을 찾을 수 없습니다.');
            }

            $role->syncPermissions($permissions);
            
            $this->loadData();
            
            session()->flash('success', '역할 권한이 성공적으로 업데이트되었습니다.');
        } catch (\Exception $e) {
            session()->flash('error', '권한 업데이트 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    public function getRolesProperty()
    {
        return Role::all()->map(function ($role) {
            $permissions = $role->permissions->pluck('name')->toArray();
            $info = $this->getRoleDisplayInfo($role->name);
            
            // 조직 내 해당 역할을 가진 멤버 수 계산
            $memberCount = $this->organization ? 
                $this->organization->users()->whereHas('roles', function ($query) use ($role) {
                    $query->where('name', $role->name);
                })->count() : 0;

            return [
                'name' => $role->name,
                'label' => $info['label'],
                'description' => $info['description'],
                'color' => $info['color'],
                'level' => $info['level'],
                'permissions' => $permissions,
                'permission_count' => count($permissions),
                'member_count' => $memberCount
            ];
        })->sortBy('level')->values()->toArray();
    }

    public function getPermissionsProperty()
    {
        return Permission::all()->map(function ($permission) {
            $roles = $permission->roles->pluck('name')->toArray();
            $category = $this->getPermissionCategory($permission->name);

            return [
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'category' => $category,
                'roles' => $roles,
                'role_count' => count($roles)
            ];
        })->groupBy('category')->toArray();
    }

    /**
     * 역할별 표시 정보 반환
     */
    private function getRoleDisplayInfo($roleName)
    {
        return match($roleName) {
            'user' => [
                'label' => '사용자',
                'description' => '기본 사용자 권한, 프로젝트 참여 및 기본 기능 사용',
                'color' => 'blue',
                'level' => 1
            ],
            'service_manager' => [
                'label' => '서비스 매니저',
                'description' => '서비스 관리 권한, 프로젝트 관리 및 팀 리딩',
                'color' => 'green',
                'level' => 2
            ],
            'organization_admin' => [
                'label' => '조직 관리자',
                'description' => '조직 관리 권한, 멤버 관리 및 조직 설정',
                'color' => 'purple',
                'level' => 3
            ],
            'organization_owner' => [
                'label' => '조직 소유자',
                'description' => '조직 소유자, 모든 조직 관리 권한',
                'color' => 'red',
                'level' => 4
            ],
            'platform_admin' => [
                'label' => '플랫폼 관리자',
                'description' => '플랫폼 관리자, 시스템 관리 권한',
                'color' => 'gray',
                'level' => 5
            ],
            default => [
                'label' => $roleName,
                'description' => '사용자 정의 역할',
                'color' => 'indigo',
                'level' => 999
            ]
        };
    }

    /**
     * 권한 카테고리 반환
     */
    private function getPermissionCategory($permissionName)
    {
        if (str_contains($permissionName, 'member')) return '멤버 관리';
        if (str_contains($permissionName, 'project')) return '프로젝트 관리';
        if (str_contains($permissionName, 'billing')) return '결제 관리';
        if (str_contains($permissionName, 'organization')) return '조직 설정';
        if (str_contains($permissionName, 'permission')) return '권한 관리';
        return '기타';
    }

    public function render()
    {
        return view('livewire.organization.admin.permission-roles', [
            'roles' => $this->getRolesProperty(),
            'permissions' => $this->getPermissionsProperty()
        ]);
    }
}