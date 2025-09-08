<?php

namespace App\Livewire\PlatformAdmin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Models\Activity;

class RoleManagement extends Component
{
    public $roles = [];
    public $selectedRole = null;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // 폼 필드
    public $name = '';
    public $guard_name = 'web';
    public $selectedPermissions = [];

    // 편집 중인 역할
    public $editingRole = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'guard_name' => 'required|string|max:255',
        'selectedPermissions' => 'array'
    ];

    public function mount()
    {
        $this->loadRoles();
    }

    public function loadRoles()
    {
        $this->roles = Role::with(['permissions', 'users'])
            ->get()
            ->map(function ($role) {
                $info = $this->getRoleDisplayInfo($role->name);
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'permissions_count' => $role->permissions->count(),
                    'users_count' => $role->users->count(),
                    'permissions' => $role->permissions->pluck('name')->toArray(),
                    'display_info' => $info,
                    'created_at' => $role->created_at,
                ];
            })
            ->sortBy('display_info.level')
            ->values()
            ->toArray();
    }

    public function selectRole($roleId)
    {
        $role = Role::with('permissions')->find($roleId);
        if ($role) {
            $this->selectedRole = [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'permissions' => $role->permissions->pluck('name')->toArray(),
                'permissions_detail' => $role->permissions->toArray(),
                'users_count' => $role->users()->count(),
                'display_info' => $this->getRoleDisplayInfo($role->name)
            ];
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($roleId)
    {
        $role = Role::with('permissions')->find($roleId);
        if ($role) {
            $this->editingRole = $role;
            $this->name = $role->name;
            $this->guard_name = $role->guard_name;
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
            $this->showEditModal = true;
        }
    }

    public function openDeleteModal($roleId)
    {
        $this->editingRole = Role::find($roleId);
        $this->showDeleteModal = true;
    }

    public function createRole()
    {
        $this->rules['name'] = [
            'required',
            'string',
            'max:255',
            Rule::unique('roles', 'name')->where(function ($query) {
                return $query->where('guard_name', $this->guard_name);
            })
        ];

        $this->validate();

        try {
            $role = Role::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name
            ]);

            if (!empty($this->selectedPermissions)) {
                $role->syncPermissions($this->selectedPermissions);
            }

            // 활동 로그 기록
            activity('permission_management')
                ->performedOn($role)
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'role_created',
                    'role_name' => $this->name,
                    'guard_name' => $this->guard_name,
                    'permissions' => $this->selectedPermissions,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log("역할 '{$this->name}' 생성");

            $this->loadRoles();
            $this->resetForm();
            $this->showCreateModal = false;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "역할 '{$this->name}'이 생성되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '역할 생성 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function updateRole()
    {
        if (!$this->editingRole) return;

        $this->rules['name'] = [
            'required',
            'string',
            'max:255',
            Rule::unique('roles', 'name')->ignore($this->editingRole->id)->where(function ($query) {
                return $query->where('guard_name', $this->guard_name);
            })
        ];

        $this->validate();

        try {
            $oldName = $this->editingRole->name;
            $oldPermissions = $this->editingRole->permissions->pluck('name')->toArray();

            $this->editingRole->update([
                'name' => $this->name,
                'guard_name' => $this->guard_name
            ]);

            $this->editingRole->syncPermissions($this->selectedPermissions);

            // 활동 로그 기록
            activity('permission_management')
                ->performedOn($this->editingRole)
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'role_updated',
                    'old' => [
                        'name' => $oldName,
                        'permissions' => $oldPermissions
                    ],
                    'attributes' => [
                        'name' => $this->name,
                        'guard_name' => $this->guard_name,
                        'permissions' => $this->selectedPermissions
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log("역할 '{$oldName}' → '{$this->name}' 수정");

            $this->loadRoles();
            $this->resetForm();
            $this->showEditModal = false;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "역할 '{$this->name}'이 업데이트되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '역할 업데이트 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteRole()
    {
        if (!$this->editingRole) return;

        try {
            $roleName = $this->editingRole->name;
            $rolePermissions = $this->editingRole->permissions->pluck('name')->toArray();

            // 활동 로그 기록
            activity('permission_management')
                ->performedOn($this->editingRole)
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'role_deleted',
                    'role_name' => $roleName,
                    'guard_name' => $this->editingRole->guard_name,
                    'permissions' => $rolePermissions,
                    'users_count' => $this->editingRole->users()->count(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log("역할 '{$roleName}' 삭제");

            $this->editingRole->delete();

            $this->loadRoles();
            $this->showDeleteModal = false;
            $this->editingRole = null;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "역할 '{$roleName}'이 삭제되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '역할 삭제 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->guard_name = 'web';
        $this->selectedPermissions = [];
        $this->editingRole = null;
        $this->resetErrorBag();
    }

    public function getPermissionsProperty()
    {
        return Permission::all()->groupBy(function ($permission) {
            return $this->getPermissionCategory($permission->name);
        });
    }

    private function getPermissionCategory($permissionName)
    {
        if (str_contains($permissionName, 'member')) return '멤버 관리';
        if (str_contains($permissionName, 'project')) return '프로젝트 관리';
        if (str_contains($permissionName, 'billing')) return '결제 관리';
        if (str_contains($permissionName, 'organization')) return '조직 설정';
        if (str_contains($permissionName, 'permission')) return '권한 관리';
        return '기타';
    }

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
                'label' => '조직 목록자',
                'description' => '조직 목록 권한, 멤버 관리 및 조직 설정',
                'color' => 'purple',
                'level' => 3
            ],
            'organization_owner' => [
                'label' => '조직 소유자',
                'description' => '조직 소유자, 모든 조직 목록 권한',
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

    public function render()
    {
        return view('900-page-platform-admin.components.922-role-management', [
            'roles' => $this->roles,
            'permissions' => $this->getPermissionsProperty(),
            'selectedRole' => $this->selectedRole
        ]);
    }
}
