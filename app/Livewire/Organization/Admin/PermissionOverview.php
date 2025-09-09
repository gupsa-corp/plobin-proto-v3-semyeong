<?php

namespace App\Livewire\Organization\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Organization;
use App\Services\DynamicPermissionService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;

class PermissionOverview extends Component
{
    public $organizationId;
    public $organization;
    
    // Enhanced properties
    public $activeView = 'overview';
    public $showQuickActions = false;
    public $searchTerm = '';
    public $filterRole = '';
    public $selectedMembers = [];
    public $bulkAction = '';
    public $bulkRole = '';

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
     * 조직 멤버 데이터 조회
     */
    public function getMembersProperty()
    {
        if (!$this->organization) {
            return [];
        }

        return $this->organization->users->map(function ($user) {
            $roles = $user->roles->pluck('name')->toArray();
            $permissions = $user->getAllPermissions()->pluck('name')->toArray();
            
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $roles,
                'primary_role' => $roles[0] ?? '일반 멤버',
                'joined_at' => $user->pivot->created_at->format('Y-m-d'),
                'permissions' => [
                    'member' => in_array('manage_organization_members', $permissions),
                    'project' => in_array('manage_organization_projects', $permissions),
                    'billing' => in_array('manage_organization_billing', $permissions),
                    'organization' => in_array('manage_organization_settings', $permissions),
                ]
            ];
        })->toArray();
    }

    /**
     * 통계 데이터 조회
     */
    public function getStatsProperty()
    {
        return [
            'totalMembers' => $this->organization ? $this->organization->users->count() : 0,
            'totalRoles' => Role::count(),
            'totalPermissions' => Permission::count()
        ];
    }
    
    /**
     * 사용 가능한 역할 조회
     */
    public function getAvailableRolesProperty()
    {
        return Role::all();
    }
    
    /**
     * 뷰 전환
     */
    public function switchView($view)
    {
        $this->activeView = $view;
    }
    
    /**
     * 빠른 작업 패널 토글
     */
    public function toggleQuickActions()
    {
        $this->showQuickActions = !$this->showQuickActions;
    }
    
    /**
     * 멤버 선택 토글
     */
    public function toggleMemberSelection($memberId)
    {
        if (in_array($memberId, $this->selectedMembers)) {
            $this->selectedMembers = array_filter($this->selectedMembers, function($id) use ($memberId) {
                return $id != $memberId;
            });
        } else {
            $this->selectedMembers[] = $memberId;
        }
    }
    
    /**
     * 일괄 작업 적용
     */
    public function applyBulkAction()
    {
        if (empty($this->selectedMembers) || empty($this->bulkAction)) {
            session()->flash('error', '멤버를 선택하고 작업을 지정해주세요.');
            return;
        }
        
        foreach ($this->selectedMembers as $memberId) {
            $user = User::find($memberId);
            if (!$user) continue;
            
            if ($this->bulkAction === 'assign_role' && $this->bulkRole) {
                $user->assignRole($this->bulkRole);
            } elseif ($this->bulkAction === 'remove_role' && $this->bulkRole) {
                $user->removeRole($this->bulkRole);
            }
        }
        
        $this->selectedMembers = [];
        $this->bulkAction = '';
        $this->bulkRole = '';
        $this->loadData();
        
        session()->flash('success', '일괄 작업이 완료되었습니다.');
    }
    
    /**
     * 사용자에서 역할 제거
     */
    public function removeRoleFromUser($userId, $roleName)
    {
        $user = User::find($userId);
        if ($user) {
            $user->removeRole($roleName);
            $this->loadData();
            session()->flash('success', "'{$roleName}' 역할이 제거되었습니다.");
        }
    }
    
    /**
     * 빠른 역할 할당
     */
    public function quickAssignRole($userId, $roleName)
    {
        $user = User::find($userId);
        if ($user) {
            $user->assignRole($roleName);
            $this->loadData();
            session()->flash('success', "'{$roleName}' 역할이 할당되었습니다.");
        }
    }

    public function render()
    {
        return view('livewire.organization.admin.302-permission-overview', [
            'members' => $this->getMembersProperty(),
            'stats' => $this->getStatsProperty()
        ]);
    }
}