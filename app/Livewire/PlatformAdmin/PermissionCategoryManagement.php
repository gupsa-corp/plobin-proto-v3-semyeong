<?php

namespace App\Livewire\PlatformAdmin;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use App\Models\PermissionCategory;
use Illuminate\Validation\Rule;

class PermissionCategoryManagement extends Component
{
    public $permissions = [];
    public $categories = [];
    public $selectedPermission = null;
    public $showCreatePermissionModal = false;
    public $showEditPermissionModal = false;
    public $showDeletePermissionModal = false;
    public $showCreateCategoryModal = false;
    public $showEditCategoryModal = false;
    public $showDeleteCategoryModal = false;
    
    // 권한 폼 필드
    public $permissionName = '';
    public $permissionGuardName = 'web';
    public $permissionCategoryId = null;
    
    // 카테고리 폼 필드
    public $categoryName = '';
    public $categoryDisplayName = '';
    public $categoryDescription = '';
    public $categoryIsActive = true;
    public $categorySortOrder = 0;
    
    // 편집 중인 항목들
    public $editingPermission = null;
    public $editingCategory = null;

    protected $rules = [
        'permissionName' => 'required|string|max:255',
        'permissionGuardName' => 'required|string|max:255',
        'permissionCategoryId' => 'nullable|exists:permission_categories,id',
        'categoryName' => 'required|string|max:255|unique:permission_categories,name',
        'categoryDisplayName' => 'required|string|max:255',
        'categoryDescription' => 'nullable|string',
        'categoryIsActive' => 'boolean',
        'categorySortOrder' => 'integer|min:0'
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->loadPermissions();
        $this->loadCategories();
    }

    protected function loadPermissions()
    {
        $this->permissions = Permission::with(['roles'])
            ->get()
            ->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'guard_name' => $permission->guard_name,
                    'roles_count' => $permission->roles->count(),
                    'roles' => $permission->roles->pluck('name')->toArray(),
                    'category' => $this->getPermissionCategory($permission->name),
                    'created_at' => $permission->created_at,
                ];
            })
            ->groupBy('category')
            ->toArray();
    }

    protected function loadCategories()
    {
        $this->categories = PermissionCategory::orderBy('sort_order')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'display_name' => $category->display_name,
                    'description' => $category->description,
                    'is_active' => $category->is_active,
                    'sort_order' => $category->sort_order,
                    'permissions_count' => $this->getCategoryPermissionsCount($category->name),
                    'created_at' => $category->created_at,
                ];
            })
            ->toArray();
    }

    private function getCategoryPermissionsCount($categoryName)
    {
        return Permission::where('name', 'like', "%{$categoryName}%")->count();
    }

    public function selectPermission($permissionId)
    {
        $permission = Permission::with('roles')->find($permissionId);
        if ($permission) {
            $this->selectedPermission = [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'roles' => $permission->roles->toArray(),
                'category' => $this->getPermissionCategory($permission->name),
                'created_at' => $permission->created_at,
            ];
        }
    }

    // 권한 관련 메소드
    public function openCreatePermissionModal()
    {
        $this->resetPermissionForm();
        $this->showCreatePermissionModal = true;
    }

    public function openEditPermissionModal($permissionId)
    {
        $permission = Permission::find($permissionId);
        if ($permission) {
            $this->editingPermission = $permission;
            $this->permissionName = $permission->name;
            $this->permissionGuardName = $permission->guard_name;
            $this->showEditPermissionModal = true;
        }
    }

    public function openDeletePermissionModal($permissionId)
    {
        $this->editingPermission = Permission::find($permissionId);
        $this->showDeletePermissionModal = true;
    }

    public function createPermission()
    {
        $this->rules['permissionName'] = [
            'required',
            'string',
            'max:255',
            Rule::unique('permissions', 'name')->where(function ($query) {
                return $query->where('guard_name', $this->permissionGuardName);
            })
        ];

        $this->validate([
            'permissionName' => $this->rules['permissionName'],
            'permissionGuardName' => $this->rules['permissionGuardName'],
        ]);

        try {
            Permission::create([
                'name' => $this->permissionName,
                'guard_name' => $this->permissionGuardName
            ]);

            $this->loadData();
            $this->resetPermissionForm();
            $this->showCreatePermissionModal = false;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "권한 '{$this->permissionName}'이 생성되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '권한 생성 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function updatePermission()
    {
        if (!$this->editingPermission) return;

        $this->rules['permissionName'] = [
            'required',
            'string',
            'max:255',
            Rule::unique('permissions', 'name')->ignore($this->editingPermission->id)->where(function ($query) {
                return $query->where('guard_name', $this->permissionGuardName);
            })
        ];

        $this->validate([
            'permissionName' => $this->rules['permissionName'],
            'permissionGuardName' => $this->rules['permissionGuardName'],
        ]);

        try {
            $this->editingPermission->update([
                'name' => $this->permissionName,
                'guard_name' => $this->permissionGuardName
            ]);

            $this->loadData();
            $this->resetPermissionForm();
            $this->showEditPermissionModal = false;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "권한 '{$this->permissionName}'이 업데이트되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '권한 업데이트 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function deletePermission()
    {
        if (!$this->editingPermission) return;

        try {
            $permissionName = $this->editingPermission->name;
            $this->editingPermission->delete();

            $this->loadData();
            $this->showDeletePermissionModal = false;
            $this->editingPermission = null;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "권한 '{$permissionName}'이 삭제되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '권한 삭제 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    // 카테고리 관련 메소드
    public function openCreateCategoryModal()
    {
        $this->resetCategoryForm();
        $this->showCreateCategoryModal = true;
    }

    public function openEditCategoryModal($categoryId)
    {
        $category = PermissionCategory::find($categoryId);
        if ($category) {
            $this->editingCategory = $category;
            $this->categoryName = $category->name;
            $this->categoryDisplayName = $category->display_name;
            $this->categoryDescription = $category->description;
            $this->categoryIsActive = $category->is_active;
            $this->categorySortOrder = $category->sort_order;
            $this->showEditCategoryModal = true;
        }
    }

    public function openDeleteCategoryModal($categoryId)
    {
        $this->editingCategory = PermissionCategory::find($categoryId);
        $this->showDeleteCategoryModal = true;
    }

    public function createCategory()
    {
        $this->validate([
            'categoryName' => 'required|string|max:255|unique:permission_categories,name',
            'categoryDisplayName' => 'required|string|max:255',
            'categoryDescription' => 'nullable|string',
            'categoryIsActive' => 'boolean',
            'categorySortOrder' => 'integer|min:0'
        ]);

        try {
            PermissionCategory::create([
                'name' => $this->categoryName,
                'display_name' => $this->categoryDisplayName,
                'description' => $this->categoryDescription,
                'is_active' => $this->categoryIsActive,
                'sort_order' => $this->categorySortOrder
            ]);

            $this->loadData();
            $this->resetCategoryForm();
            $this->showCreateCategoryModal = false;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "카테고리 '{$this->categoryDisplayName}'이 생성되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '카테고리 생성 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function updateCategory()
    {
        if (!$this->editingCategory) return;

        $this->validate([
            'categoryName' => 'required|string|max:255|unique:permission_categories,name,' . $this->editingCategory->id,
            'categoryDisplayName' => 'required|string|max:255',
            'categoryDescription' => 'nullable|string',
            'categoryIsActive' => 'boolean',
            'categorySortOrder' => 'integer|min:0'
        ]);

        try {
            $this->editingCategory->update([
                'name' => $this->categoryName,
                'display_name' => $this->categoryDisplayName,
                'description' => $this->categoryDescription,
                'is_active' => $this->categoryIsActive,
                'sort_order' => $this->categorySortOrder
            ]);

            $this->loadData();
            $this->resetCategoryForm();
            $this->showEditCategoryModal = false;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "카테고리 '{$this->categoryDisplayName}'이 업데이트되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '카테고리 업데이트 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteCategory()
    {
        if (!$this->editingCategory) return;

        try {
            $categoryName = $this->editingCategory->display_name;
            $this->editingCategory->delete();

            $this->loadData();
            $this->showDeleteCategoryModal = false;
            $this->editingCategory = null;

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "카테고리 '{$categoryName}'이 삭제되었습니다."
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '카테고리 삭제 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function closeModals()
    {
        $this->showCreatePermissionModal = false;
        $this->showEditPermissionModal = false;
        $this->showDeletePermissionModal = false;
        $this->showCreateCategoryModal = false;
        $this->showEditCategoryModal = false;
        $this->showDeleteCategoryModal = false;
        $this->resetPermissionForm();
        $this->resetCategoryForm();
    }

    private function resetPermissionForm()
    {
        $this->permissionName = '';
        $this->permissionGuardName = 'web';
        $this->permissionCategoryId = null;
        $this->editingPermission = null;
        $this->resetErrorBag(['permissionName', 'permissionGuardName', 'permissionCategoryId']);
    }

    private function resetCategoryForm()
    {
        $this->categoryName = '';
        $this->categoryDisplayName = '';
        $this->categoryDescription = '';
        $this->categoryIsActive = true;
        $this->categorySortOrder = 0;
        $this->editingCategory = null;
        $this->resetErrorBag(['categoryName', 'categoryDisplayName', 'categoryDescription', 'categoryIsActive', 'categorySortOrder']);
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

    public function render()
    {
        return view('900-page-platform-admin.components.923-permission-category-management', [
            'permissions' => $this->permissions,
            'categories' => $this->categories,
            'selectedPermission' => $this->selectedPermission
        ]);
    }
}