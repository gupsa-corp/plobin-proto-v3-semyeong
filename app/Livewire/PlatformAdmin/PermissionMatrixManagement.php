<?php

namespace App\Livewire\PlatformAdmin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class PermissionMatrixManagement extends Component
{
    public $scope = 'platform'; // platform or organization
    public $selectedOrganizationId = null;
    public $searchTerm = '';
    public $selectedCategory = '';
    
    public $permissionsData = [];
    public $rolesData = [];
    public $permissionMatrix = [];
    
    public $stats = [
        'total_permissions' => 0,
        'total_roles' => 0,
        'total_categories' => 0
    ];

    public $categories = [];
    
    protected $listeners = [
        'scopeChanged' => 'handleScopeChange',
        'permissionToggled' => 'handlePermissionToggle',
        'refreshMatrix' => 'loadData'
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function updatedScope()
    {
        $this->dispatch('scope-changed', [
            'scope' => $this->scope,
            'organizationId' => $this->selectedOrganizationId
        ]);
        
        if ($this->scope === 'platform') {
            $this->selectedOrganizationId = null;
        }
        
        $this->loadData();
    }

    public function updatedSelectedOrganizationId()
    {
        $this->dispatch('scope-changed', [
            'scope' => $this->scope,
            'organizationId' => $this->selectedOrganizationId
        ]);
        
        $this->loadData();
    }

    public function updatedSearchTerm()
    {
        // Real-time search will be handled by JavaScript
    }

    public function updatedSelectedCategory()
    {
        // Real-time filter will be handled by JavaScript
    }

    public function handleScopeChange($data)
    {
        $this->scope = $data['scope'];
        $this->selectedOrganizationId = $data['organizationId'] ?? null;
        $this->loadData();
    }

    protected function loadData()
    {
        $this->loadPermissionsAndRoles();
        $this->loadPermissionMatrix();
        $this->updateStats();
        $this->loadCategories();
    }

    protected function loadPermissionsAndRoles()
    {
        // Load permissions grouped by category
        $permissions = Permission::all()->groupBy(function ($permission) {
            return $this->getPermissionCategory($permission->name);
        });

        $this->permissionsData = $permissions->map(function ($categoryPermissions) {
            return $categoryPermissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'description' => $this->getPermissionDescription($permission->name),
                ];
            })->toArray();
        })->toArray();

        // Load roles based on scope
        if ($this->scope === 'organization' && $this->selectedOrganizationId) {
            // Load organization-specific roles
            $this->rolesData = Role::where('organization_id', $this->selectedOrganizationId)
                ->orWhereNull('organization_id')
                ->orderBy('name')
                ->pluck('name')
                ->toArray();
        } else {
            // Load platform roles
            $this->rolesData = Role::whereNull('organization_id')
                ->orderBy('name')
                ->pluck('name')
                ->toArray();
        }
    }

    protected function loadPermissionMatrix()
    {
        $matrix = [];
        
        foreach ($this->rolesData as $roleName) {
            $role = Role::findByName($roleName);
            $rolePermissions = $role->permissions->pluck('name')->toArray();
            
            $matrix[$roleName] = [];
            foreach ($this->permissionsData as $category => $permissions) {
                $matrix[$roleName][$category] = [];
                foreach ($permissions as $permission) {
                    $matrix[$roleName][$category][$permission['name']] = in_array($permission['name'], $rolePermissions);
                }
            }
        }

        $this->permissionMatrix = $matrix;
    }

    protected function updateStats()
    {
        $totalPermissions = collect($this->permissionsData)->sum(function ($permissions) {
            return count($permissions);
        });

        $this->stats = [
            'total_permissions' => $totalPermissions,
            'total_roles' => count($this->rolesData),
            'total_categories' => count($this->permissionsData)
        ];
    }

    protected function loadCategories()
    {
        $this->categories = array_keys($this->permissionsData);
    }

    public function toggleRolePermission($roleName, $permissionName, $isChecked)
    {
        try {
            $role = Role::findByName($roleName);
            
            if ($isChecked) {
                $role->givePermissionTo($permissionName);
            } else {
                $role->revokePermissionTo($permissionName);
            }

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => '권한이 성공적으로 업데이트되었습니다.'
            ]);

            $this->dispatch('permissionToggled', [
                'role' => $roleName,
                'permission' => $permissionName,
                'granted' => $isChecked
            ]);

            // Refresh the matrix to reflect changes
            $this->loadPermissionMatrix();

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '권한 업데이트 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function selectAllForRole($roleName)
    {
        try {
            $role = Role::findByName($roleName);
            $allPermissions = Permission::pluck('name')->toArray();
            
            // Check if all permissions are already assigned
            $currentPermissions = $role->permissions->pluck('name')->toArray();
            $hasAllPermissions = empty(array_diff($allPermissions, $currentPermissions));
            
            if ($hasAllPermissions) {
                // Remove all permissions
                $role->revokePermissionTo($allPermissions);
                $message = "{$roleName} 역할의 모든 권한이 제거되었습니다.";
            } else {
                // Grant all permissions
                $role->syncPermissions($allPermissions);
                $message = "{$roleName} 역할에 모든 권한이 할당되었습니다.";
            }

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => $message
            ]);

            $this->loadPermissionMatrix();

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '일괄 권한 업데이트 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function createPermission($name, $category, $description = '')
    {
        $this->validate([
            'name' => 'required|string|unique:permissions,name',
            'category' => 'required|string',
        ], [
            'name.required' => '권한 이름은 필수입니다.',
            'name.unique' => '이미 존재하는 권한 이름입니다.',
            'category.required' => '카테고리는 필수입니다.',
        ]);

        try {
            Permission::create([
                'name' => $name,
                'guard_name' => 'web',
                // You might want to store category and description in a custom field
            ]);

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => '새 권한이 생성되었습니다.'
            ]);

            $this->loadData();

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '권한 생성 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function exportPermissions()
    {
        try {
            $exportData = [
                'roles' => $this->rolesData,
                'permissions' => $this->permissionsData,
                'matrix' => $this->permissionMatrix,
                'stats' => $this->stats,
                'exported_at' => now()->toISOString(),
                'scope' => $this->scope,
                'organization_id' => $this->selectedOrganizationId
            ];

            $this->dispatch('download-export', [
                'data' => $exportData,
                'filename' => 'permissions-export-' . now()->format('Y-m-d') . '.json'
            ]);

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => '권한 데이터가 내보내졌습니다.'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '내보내기 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function getOrganizations()
    {
        return Organization::orderBy('name')->get();
    }

    protected function getPermissionCategory($permissionName)
    {
        // Extract category from permission name (e.g., 'users.create' -> 'users')
        $parts = explode('.', $permissionName);
        return count($parts) > 1 ? $parts[0] : '기타';
    }

    protected function getPermissionDescription($permissionName)
    {
        // You can create a mapping for permission descriptions
        $descriptions = [
            'users.create' => '사용자 생성',
            'users.read' => '사용자 조회',
            'users.update' => '사용자 수정',
            'users.delete' => '사용자 삭제',
            'organizations.create' => '조직 생성',
            'organizations.read' => '조직 조회',
            'organizations.update' => '조직 수정',
            'organizations.delete' => '조직 삭제',
            // Add more as needed
        ];

        return $descriptions[$permissionName] ?? '';
    }

    public function handlePermissionToggle($data)
    {
        // Handle permission toggle event if needed for real-time updates
    }

    public function render()
    {
        return view('livewire.platform-admin.104-permission-matrix-management', [
            'organizations' => $this->getOrganizations(),
        ]);
    }
}