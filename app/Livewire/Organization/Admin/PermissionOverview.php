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

    public function render()
    {
        return view('livewire.organization.admin.permission-overview', [
            'members' => $this->getMembersProperty(),
            'stats' => $this->getStatsProperty()
        ]);
    }
}