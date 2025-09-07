<?php

namespace App\Livewire\PlatformAdmin;

use Livewire\Component;
use App\Models\Role;
use App\Models\User;
use App\Models\Organization;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Models\Activity;

class HierarchicalRoleManagement extends Component
{
    public $roles = [];
    public $filteredRoles = [];
    public $selectedRole = null;
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    
    // 필터 속성들
    public $filterScopeLevel = '';
    public $filterOrganization = '';
    public $filterStatus = '';
    public $searchTerm = '';
    
    // 폼 필드
    public $name = '';
    public $guard_name = 'web';
    public $selectedPermissions = [];
    
    // 계층형 필드들
    public $scope_level = 'platform';
    public $organization_id = null;
    public $project_id = null;
    public $page_id = null;
    public $parent_role_id = null;
    public $description = '';
    public $is_active = true;
    
    // 편집 중인 역할
    public $editingRole = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'guard_name' => 'required|string|max:255',
        'selectedPermissions' => 'array',
        'scope_level' => 'required|in:platform,organization,project,page',
        'organization_id' => 'nullable|integer|exists:organizations,id',
        'project_id' => 'nullable|integer',
        'page_id' => 'nullable|integer',
        'parent_role_id' => 'nullable|integer|exists:roles,id',
        'description' => 'nullable|string',
        'is_active' => 'boolean'
    ];

    public function mount()
    {
        $this->loadRoles();
        $this->applyFilters();
    }

    public function loadRoles()
    {
        $this->roles = Role::with([
                'permissions', 
                'users',
                'parentRole', 
                'childRoles', 
                'creator', 
                'organization'
            ])
            ->get()
            ->map(function ($role) {
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
                    'permissions_count' => $role->permissions->count(),
                    'users_count' => $role->users->count(),
                    'children_count' => $role->childRoles->count(),
                    'permissions' => $role->permissions->pluck('name')->toArray(),
                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                    
                    // 관계 정보
                    'organization' => $role->organization ? [
                        'id' => $role->organization->id,
                        'name' => $role->organization->name,
                        'slug' => $role->organization->slug ?? null,
                    ] : null,
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
                    
                    // 디스플레이 정보
                    'display_info' => $this->getScopeLevelDisplayInfo($role->scope_level),
                ];
            })
            ->sortBy(function ($role) {
                $order = ['platform' => 1, 'organization' => 2, 'project' => 3, 'page' => 4];
                return $order[$role['scope_level']] ?? 999;
            })
            ->values()
            ->toArray();
            
        $this->applyFilters();
    }
    
    public function applyFilters()
    {
        $filtered = collect($this->roles);

        // 범위 레벨 필터
        if (!empty($this->filterScopeLevel)) {
            $filtered = $filtered->filter(function ($role) {
                return $role['scope_level'] === $this->filterScopeLevel;
            });
        }

        // 조직 필터
        if (!empty($this->filterOrganization)) {
            $filtered = $filtered->filter(function ($role) {
                return $role['organization'] && $role['organization']['id'] == $this->filterOrganization;
            });
        }

        // 상태 필터
        if (!empty($this->filterStatus)) {
            $filtered = $filtered->filter(function ($role) {
                return $this->filterStatus === 'active' ? $role['is_active'] : !$role['is_active'];
            });
        }

        // 검색어 필터
        if (!empty($this->searchTerm)) {
            $filtered = $filtered->filter(function ($role) {
                return str_contains(strtolower($role['name']), strtolower($this->searchTerm)) ||
                       str_contains(strtolower($role['description'] ?? ''), strtolower($this->searchTerm));
            });
        }

        $this->filteredRoles = $filtered->values()->toArray();
    }

    public function updatedFilterScopeLevel()
    {
        $this->applyFilters();
    }

    public function updatedFilterOrganization()
    {
        $this->applyFilters();
    }

    public function updatedFilterStatus()
    {
        $this->applyFilters();
    }

    public function updatedSearchTerm()
    {
        $this->applyFilters();
    }

    public function clearFilters()
    {
        $this->filterScopeLevel = '';
        $this->filterOrganization = '';
        $this->filterStatus = '';
        $this->searchTerm = '';
        $this->applyFilters();
    }

    public function selectRole($roleId)
    {
        $role = Role::with(['permissions', 'parentRole', 'creator', 'organization'])->find($roleId);
        if ($role) {
            $this->selectedRole = [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'scope_level' => $role->scope_level,
                'description' => $role->description,
                'hierarchy_path' => $role->getHierarchyPath(),
                'permissions' => $role->permissions->pluck('name')->toArray(),
                'permissions_detail' => $role->permissions->toArray(),
                'users_count' => $role->users()->count(),
                'display_info' => $this->getScopeLevelDisplayInfo($role->scope_level),
                'parent_role' => $role->parentRole,
                'creator' => $role->creator,
                'organization' => $role->organization,
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
            $this->scope_level = $role->scope_level;
            $this->organization_id = $role->organization_id;
            $this->project_id = $role->project_id;
            $this->page_id = $role->page_id;
            $this->parent_role_id = $role->parent_role_id;
            $this->description = $role->description;
            $this->is_active = $role->is_active;
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
                'guard_name' => $this->guard_name,
                'scope_level' => $this->scope_level,
                'organization_id' => $this->organization_id,
                'project_id' => $this->project_id,
                'page_id' => $this->page_id,
                'parent_role_id' => $this->parent_role_id,
                'created_by' => auth()->id(),
                'description' => $this->description,
                'is_active' => $this->is_active,
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
                    'scope_level' => $this->scope_level,
                    'guard_name' => $this->guard_name,
                    'permissions' => $this->selectedPermissions,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log("계층형 역할 '{$this->name}' 생성");

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
            $oldData = [
                'name' => $this->editingRole->name,
                'scope_level' => $this->editingRole->scope_level,
                'permissions' => $this->editingRole->permissions->pluck('name')->toArray()
            ];
            
            $this->editingRole->update([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'scope_level' => $this->scope_level,
                'organization_id' => $this->organization_id,
                'project_id' => $this->project_id,
                'page_id' => $this->page_id,
                'parent_role_id' => $this->parent_role_id,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);

            $this->editingRole->syncPermissions($this->selectedPermissions);

            // 활동 로그 기록
            activity('permission_management')
                ->performedOn($this->editingRole)
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'role_updated',
                    'old' => $oldData,
                    'attributes' => [
                        'name' => $this->name,
                        'scope_level' => $this->scope_level,
                        'guard_name' => $this->guard_name,
                        'permissions' => $this->selectedPermissions
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log("계층형 역할 '{$oldData['name']}' → '{$this->name}' 수정");

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
            $roleData = [
                'name' => $roleName,
                'scope_level' => $this->editingRole->scope_level,
                'permissions' => $this->editingRole->permissions->pluck('name')->toArray(),
                'users_count' => $this->editingRole->users()->count(),
            ];

            // 활동 로그 기록
            activity('permission_management')
                ->performedOn($this->editingRole)
                ->causedBy(auth()->user())
                ->withProperties([
                    'action' => 'role_deleted',
                    'role_data' => $roleData,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log("계층형 역할 '{$roleName}' 삭제");

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
        $this->scope_level = 'platform';
        $this->organization_id = null;
        $this->project_id = null;
        $this->page_id = null;
        $this->parent_role_id = null;
        $this->description = '';
        $this->is_active = true;
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
        if (str_contains($permissionName, 'dashboard')) return '대시보드';
        if (str_contains($permissionName, 'user')) return '사용자 관리';
        if (str_contains($permissionName, 'organization')) return '조직 관리';
        if (str_contains($permissionName, 'project')) return '프로젝트 관리';
        if (str_contains($permissionName, 'page')) return '페이지 관리';
        if (str_contains($permissionName, 'permission')) return '권한 관리';
        if (str_contains($permissionName, 'billing')) return '결제 관리';
        if (str_contains($permissionName, 'report')) return '보고서';
        return '기타';
    }

    private function getScopeLevelDisplayInfo($scopeLevel)
    {
        $displayMap = [
            'platform' => [
                'label' => '플랫폼',
                'description' => '플랫폼 전체 범위의 역할',
                'color' => 'red',
                'icon' => '🏢',
                'level' => 1
            ],
            'organization' => [
                'label' => '조직',
                'description' => '특정 조직 범위의 역할',
                'color' => 'blue',
                'icon' => '🏢',
                'level' => 2
            ],
            'project' => [
                'label' => '프로젝트',
                'description' => '특정 프로젝트 범위의 역할',
                'color' => 'green',
                'icon' => '📁',
                'level' => 3
            ],
            'page' => [
                'label' => '페이지',
                'description' => '특정 페이지 범위의 역할',
                'color' => 'purple',
                'icon' => '📄',
                'level' => 4
            ]
        ];
        
        return $displayMap[$scopeLevel] ?? [
            'label' => $scopeLevel,
            'description' => '사용자 정의 범위',
            'color' => 'gray',
            'icon' => '❓',
            'level' => 999
        ];
    }
    
    public function getAvailableOrganizationsProperty()
    {
        try {
            return Organization::orderBy('name')->get();
        } catch (\Exception $e) {
            // Organization 테이블이나 모델이 없는 경우 빈 컬렉션 반환
            return collect([]);
        }
    }
    
    public function getAvailableParentRolesProperty()
    {
        return Role::where('is_active', true)
            ->where('id', '!=', $this->editingRole?->id ?? 0)
            ->orderBy('scope_level')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('900-page-platform-admin.components.923-hierarchical-role-management', [
            'roles' => $this->filteredRoles,
            'permissions' => $this->getPermissionsProperty(),
            'selectedRole' => $this->selectedRole,
            'availableOrganizations' => $this->getAvailableOrganizationsProperty(),
            'availableParentRoles' => $this->getAvailableParentRolesProperty(),
            'scopeLevels' => [
                'platform' => '플랫폼',
                'organization' => '조직',
                'project' => '프로젝트',
                'page' => '페이지'
            ],
            'totalRoles' => count($this->roles),
            'filteredCount' => count($this->filteredRoles),
        ]);
    }
}