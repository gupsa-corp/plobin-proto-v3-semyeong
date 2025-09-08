<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProjectSettingsPermissions extends Component
{
    public $projectId;
    public $organizationId;
    
    // 커스텀 역할 생성
    public $newRoleName = '';
    public $selectedPermissions = [];
    public $showCreateRoleForm = false;
    
    // 커스텀 역할 선택
    public $selectedRole = '';
    public $showRoleSelector = false;

    public function mount($projectId, $organizationId)
    {
        $this->projectId = $projectId;
        $this->organizationId = $organizationId;
    }

    public function createCustomRole()
    {
        $this->validate([
            'newRoleName' => 'required|string|max:255',
            'selectedPermissions' => 'array'
        ]);

        // 프로젝트별 커스텀 역할 생성
        $roleName = 'project_' . $this->projectId . '_' . str_replace(' ', '_', strtolower($this->newRoleName));
        
        $role = Role::create([
            'name' => $roleName,
            'display_name' => $this->newRoleName,
            'project_id' => $this->projectId,
            'guard_name' => 'web'
        ]);

        // 권한 부여
        if (!empty($this->selectedPermissions)) {
            $role->givePermissionTo($this->selectedPermissions);
        }

        // 폼 초기화
        $this->newRoleName = '';
        $this->selectedPermissions = [];
        $this->showCreateRoleForm = false;

        session()->flash('message', '커스텀 역할이 생성되었습니다.');
    }

    public function selectRole($roleId)
    {
        $this->selectedRole = $roleId;
        $this->showRoleSelector = false;
        
        session()->flash('message', '역할이 선택되었습니다.');
    }

    public function getCustomRolesProperty()
    {
        return Role::where('name', 'like', 'project_' . $this->projectId . '_%')
                   ->get()
                   ->map(function ($role) {
                       return [
                           'id' => $role->id,
                           'name' => $role->display_name ?? $role->name,
                           'permissions' => $role->permissions->pluck('name')->toArray()
                       ];
                   });
    }

    public function getAvailablePermissionsProperty()
    {
        return [
            'project.read' => '프로젝트 읽기',
            'project.write' => '프로젝트 수정',
            'project.deploy' => '배포 관리',
            'project.members' => '멤버 관리',
            'pages.read' => '페이지 조회',
            'pages.write' => '페이지 수정',
            'pages.delete' => '페이지 삭제'
        ];
    }

    public function render()
    {
        return view('livewire.project-settings-permissions');
    }
}