<?php

namespace App\Livewire\PlatformAdmin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Organization;

class UserPermissionManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedRole = '';
    public $selectedOrganization = '';
    public $selectedPermissionLevel = '';
    
    public $showRoleChangeModal = false;
    public $showStatusChangeModal = false;
    public $showTenantPermissionModal = false;
    
    public $selectedUser = null;
    public $newRole = '';
    public $statusChangeMessage = '';
    public $tenantPermissions = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedRole' => ['except' => ''],
        'selectedOrganization' => ['except' => ''],
        'selectedPermissionLevel' => ['except' => ''],
    ];

    protected $listeners = [
        'refreshUsers' => '$refresh',
        'userRoleChanged' => 'handleRoleChanged',
        'userStatusChanged' => 'handleStatusChanged',
        'tenantPermissionsUpdated' => 'handleTenantPermissionsUpdated'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedRole()
    {
        $this->resetPage();
    }

    public function updatingSelectedOrganization()
    {
        $this->resetPage();
    }

    public function updatingSelectedPermissionLevel()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedRole = '';
        $this->selectedOrganization = '';
        $this->selectedPermissionLevel = '';
        $this->resetPage();
    }

    public function openRoleChangeModal(User $user)
    {
        $this->selectedUser = $user;
        $this->newRole = '';
        $this->showRoleChangeModal = true;
    }

    public function closeRoleChangeModal()
    {
        $this->selectedUser = null;
        $this->newRole = '';
        $this->showRoleChangeModal = false;
    }

    public function saveRoleChange()
    {
        $this->validate([
            'newRole' => 'required|string|exists:roles,name'
        ]);

        if (!$this->selectedUser) {
            return;
        }

        try {
            // Remove all existing roles and assign the new one
            $this->selectedUser->syncRoles([$this->newRole]);
            
            $this->dispatch('notification', [
                'type' => 'success',
                'message' => '사용자 역할이 성공적으로 변경되었습니다.'
            ]);

            $this->dispatch('userRoleChanged', [
                'userId' => $this->selectedUser->id,
                'newRole' => $this->newRole
            ]);

            $this->closeRoleChangeModal();
            
        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '역할 변경 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleUserStatus(User $user)
    {
        $this->selectedUser = $user;
        $this->statusChangeMessage = $user->is_active 
            ? "정말 {$user->name}을(를) 비활성화 하시겠습니까?"
            : "정말 {$user->name}을(를) 활성화 하시겠습니까?";
        $this->showStatusChangeModal = true;
    }

    public function closeStatusChangeModal()
    {
        $this->selectedUser = null;
        $this->statusChangeMessage = '';
        $this->showStatusChangeModal = false;
    }

    public function confirmStatusChange()
    {
        if (!$this->selectedUser) {
            return;
        }

        try {
            $this->selectedUser->update([
                'is_active' => !$this->selectedUser->is_active
            ]);

            $status = $this->selectedUser->is_active ? '활성화' : '비활성화';
            
            $this->dispatch('notification', [
                'type' => 'success',
                'message' => "사용자가 성공적으로 {$status}되었습니다."
            ]);

            $this->dispatch('userStatusChanged', [
                'userId' => $this->selectedUser->id,
                'isActive' => $this->selectedUser->is_active
            ]);

            $this->closeStatusChangeModal();
            
        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => '상태 변경 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function openTenantPermissionModal(User $user)
    {
        $this->selectedUser = $user;
        $this->loadUserTenantPermissions();
        $this->showTenantPermissionModal = true;
    }

    public function closeTenantPermissionModal()
    {
        $this->selectedUser = null;
        $this->tenantPermissions = [];
        $this->showTenantPermissionModal = false;
    }

    protected function loadUserTenantPermissions()
    {
        if (!$this->selectedUser) {
            return;
        }

        // Load user's organization permissions
        // This is a simplified version - you'll need to implement based on your organization permission system
        $this->tenantPermissions = $this->selectedUser->organizations()
            ->withPivot('permission_level', 'role')
            ->get()
            ->map(function ($org) {
                return [
                    'organization_id' => $org->id,
                    'organization_name' => $org->name,
                    'permission_level' => $org->pivot->permission_level ?? 100,
                    'role' => $org->pivot->role ?? 'member'
                ];
            })
            ->toArray();
    }

    protected function getUsers()
    {
        return User::query()
            ->with(['roles', 'organizations'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->selectedRole, function ($query) {
                if ($this->selectedRole === 'no_role') {
                    $query->doesntHave('roles');
                } else {
                    $query->whereHas('roles', function ($q) {
                        $q->where('name', $this->selectedRole);
                    });
                }
            })
            ->when($this->selectedOrganization, function ($query) {
                if ($this->selectedOrganization === 'no_org') {
                    $query->doesntHave('organizations');
                } else {
                    $query->whereHas('organizations', function ($q) {
                        $q->where('organizations.id', $this->selectedOrganization);
                    });
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function getRoles()
    {
        return Role::orderBy('name')->get();
    }

    public function getOrganizations()
    {
        return Organization::orderBy('name')->get();
    }

    public function handleRoleChanged($data)
    {
        // Handle the role change event if needed
    }

    public function handleStatusChanged($data)
    {
        // Handle the status change event if needed
    }

    public function handleTenantPermissionsUpdated($data)
    {
        // Handle tenant permissions update event if needed
    }

    public function render()
    {
        return view('livewire.platform-admin.user-permission-management', [
            'users' => $this->getUsers(),
            'roles' => $this->getRoles(),
            'organizations' => $this->getOrganizations(),
        ]);
    }
}