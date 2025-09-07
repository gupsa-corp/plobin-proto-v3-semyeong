<?php

namespace App\Livewire\PlatformAdmin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\DynamicPermissionService;
use App\Models\DynamicPermissionRule;

class PermissionOverview extends Component
{
    public $systemStats = [];
    public $permissionMatrix = [];
    public $recentChanges = [];

    public function mount()
    {
        $this->loadSystemStats();
        $this->loadPermissionMatrix();
        $this->loadRecentChanges();
    }

    protected function loadSystemStats()
    {
        $this->systemStats = [
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'total_rules' => DynamicPermissionRule::count(),
            'active_rules' => DynamicPermissionRule::where('is_active', true)->count(),
        ];
    }

    protected function loadPermissionMatrix()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        
        $this->permissionMatrix = $roles->map(function ($role) use ($permissions) {
            $rolePermissions = $role->permissions->pluck('name')->toArray();
            
            return [
                'role' => $role,
                'permissions' => $permissions->map(function ($permission) use ($rolePermissions) {
                    return [
                        'permission' => $permission,
                        'has_permission' => in_array($permission->name, $rolePermissions)
                    ];
                })->toArray()
            ];
        })->toArray();
    }

    protected function loadRecentChanges()
    {
        // 최근 변경사항은 실제로는 audit log에서 가져와야 하지만
        // 여기서는 간단히 최근 생성된 규칙들을 보여줌
        $this->recentChanges = DynamicPermissionRule::latest()
            ->take(10)
            ->get()
            ->map(function ($rule) {
                return [
                    'id' => $rule->id,
                    'type' => 'rule_update',
                    'description' => "동적 규칙 '{$rule->resource_type}.{$rule->action}' 업데이트",
                    'created_at' => $rule->created_at,
                    'is_active' => $rule->is_active
                ];
            })
            ->toArray();
    }

    public function refreshData()
    {
        $this->loadSystemStats();
        $this->loadPermissionMatrix();
        $this->loadRecentChanges();
        
        $this->dispatch('notification', [
            'type' => 'success',
            'message' => '데이터가 새로고침되었습니다.'
        ]);
    }

    public function render()
    {
        return view('900-page-platform-admin.components.921-permission-overview', [
            'systemStats' => $this->systemStats,
            'permissionMatrix' => $this->permissionMatrix,
            'recentChanges' => $this->recentChanges
        ]);
    }
}