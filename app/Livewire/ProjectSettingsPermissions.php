<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectPage;
use App\Models\OrganizationMember;
use App\Models\ProjectMemberRole;
use App\Enums\ProjectRole;
use App\Enums\PageAccessLevel;
use App\Services\AccessControlService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProjectSettingsPermissions extends Component
{
    public $projectId;
    public $organizationId;
    
    // 프로젝트 기본 접근 권한
    public $projectDefaultAccess = '';
    
    // 멤버 역할 관리
    public $memberRoles = [];
    public $bulkRole = '';
    public $selectedMembers = [];
    
    // 페이지 접근 권한
    public $pageAccessLevels = [];
    public $bulkPageAccess = '';
    public $selectedPages = [];
    
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
        
        $this->loadProjectData();
        $this->loadMemberRoles();
        $this->loadPageAccessLevels();
    }
    
    protected function loadProjectData()
    {
        $project = Project::find($this->projectId);
        $this->projectDefaultAccess = $project->default_access_level ?? ProjectRole::GUEST->value;
    }
    
    protected function loadMemberRoles()
    {
        $members = OrganizationMember::where('organization_id', $this->organizationId)
            ->with('user')
            ->get();
            
        foreach ($members as $member) {
            $currentRole = $this->getUserProjectRole($member->user_id);
            $this->memberRoles[$member->user_id] = [
                'user' => [
                    'name' => $member->user->name ?? '',
                    'email' => $member->user->email ?? ''
                ],
                'current_role' => $currentRole,
                'new_role' => $currentRole
            ];
        }
    }
    
    protected function loadPageAccessLevels()
    {
        $pages = ProjectPage::where('project_id', $this->projectId)->get();
        
        foreach ($pages as $page) {
            $this->pageAccessLevels[$page->id] = [
                'title' => $page->title,
                'path' => $page->slug,
                'current_access' => $page->access_level ?? PageAccessLevel::PUBLIC->value,
                'new_access' => $page->access_level ?? PageAccessLevel::PUBLIC->value
            ];
        }
    }
    
    protected function getUserProjectRole($userId)
    {
        $memberRole = ProjectMemberRole::where('project_id', $this->projectId)
            ->where('user_id', $userId)
            ->first();
            
        if ($memberRole) {
            return $memberRole->role_name;
        }
        
        // 조직 역할에서 기본 역할 가져오기
        $orgMember = OrganizationMember::where('organization_id', $this->organizationId)
            ->where('user_id', $userId)
            ->first();
            
        return $orgMember->role_name ?? ProjectRole::GUEST->value;
    }
    
    // 프로젝트 접근 권한 업데이트
    public function updateProjectAccess()
    {
        $project = Project::find($this->projectId);
        $project->update([
            'default_access_level' => $this->projectDefaultAccess
        ]);
        
        session()->flash('message', '프로젝트 기본 접근 권한이 업데이트되었습니다.');
    }
    
    // 멤버 역할 업데이트
    public function updateMemberRole($userId)
    {
        $newRole = $this->memberRoles[$userId]['new_role'];
        
        ProjectMemberRole::updateOrCreate([
            'project_id' => $this->projectId,
            'user_id' => $userId
        ], [
            'role_name' => $newRole
        ]);
        
        // 현재 역할 업데이트
        $this->memberRoles[$userId]['current_role'] = $newRole;
        
        session()->flash('message', '멤버 역할이 업데이트되었습니다.');
    }
    
    // 페이지 접근 권한 업데이트
    public function updatePageAccess($pageId)
    {
        $newAccess = $this->pageAccessLevels[$pageId]['new_access'];
        
        $page = ProjectPage::find($pageId);
        $page->update([
            'access_level' => $newAccess
        ]);
        
        // 현재 접근 권한 업데이트
        $this->pageAccessLevels[$pageId]['current_access'] = $newAccess;
        
        session()->flash('message', '페이지 접근 권한이 업데이트되었습니다.');
    }
    
    // 일괄 역할 업데이트
    public function bulkUpdateRoles()
    {
        if (empty($this->bulkRole) || empty($this->selectedMembers)) {
            return;
        }
        
        foreach ($this->selectedMembers as $userId) {
            ProjectMemberRole::updateOrCreate([
                'project_id' => $this->projectId,
                'user_id' => $userId
            ], [
                'role_name' => $this->bulkRole
            ]);
            
            $this->memberRoles[$userId]['current_role'] = $this->bulkRole;
            $this->memberRoles[$userId]['new_role'] = $this->bulkRole;
        }
        
        $this->selectedMembers = [];
        $this->bulkRole = '';
        
        session()->flash('message', '선택된 멤버들의 역할이 일괄 업데이트되었습니다.');
    }
    
    // 페이지 일괄 접근 권한 업데이트
    public function bulkUpdatePageAccess()
    {
        if (empty($this->bulkPageAccess) || empty($this->selectedPages)) {
            return;
        }
        
        foreach ($this->selectedPages as $pageId) {
            $page = ProjectPage::find($pageId);
            $page->update([
                'access_level' => $this->bulkPageAccess
            ]);
            
            $this->pageAccessLevels[$pageId]['current_access'] = $this->bulkPageAccess;
            $this->pageAccessLevels[$pageId]['new_access'] = $this->bulkPageAccess;
        }
        
        $this->selectedPages = [];
        $this->bulkPageAccess = '';
        
        session()->flash('message', '선택된 페이지들의 접근 권한이 일괄 업데이트되었습니다.');
    }
    
    // 커스텀 역할 편집/삭제
    public function editCustomRole($roleId)
    {
        // 편집 기능 구현
        session()->flash('message', '커스텀 역할 편집 기능은 추후 구현 예정입니다.');
    }
    
    public function deleteCustomRole($roleId)
    {
        $role = Role::find($roleId);
        if ($role) {
            $role->delete();
            session()->flash('message', '커스텀 역할이 삭제되었습니다.');
        }
    }

    public function createCustomRole()
    {
        $this->validate([
            'newRoleName' => 'required|string|max:255',
            'selectedPermissions' => 'array'
        ]);

        try {
            // 프로젝트별 커스텀 역할 생성
            $roleName = 'project_' . $this->projectId . '_' . str_replace(' ', '_', strtolower($this->newRoleName));
            
            $role = Role::create([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);

            // 권한 부여 - 존재하는 권한만 부여
            if (!empty($this->selectedPermissions)) {
                $permissions = Permission::whereIn('name', $this->selectedPermissions)->get();
                if ($permissions->count() > 0) {
                    $role->syncPermissions($permissions);
                }
            }

            // 폼 초기화
            $this->newRoleName = '';
            $this->selectedPermissions = [];
            $this->showCreateRoleForm = false;

            session()->flash('message', '커스텀 역할이 생성되었습니다.');
        } catch (\Exception $e) {
            session()->flash('error', '역할 생성 중 오류가 발생했습니다: ' . $e->getMessage());
        }
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
                           'name' => $role->name,
                           'permissions' => $role->permissions->pluck('name')->toArray(),
                           'created_at' => $role->created_at
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
    
    // 접근 권한 레벨 옵션
    public function getAccessLevelOptionsProperty()
    {
        $options = [];
        foreach (PageAccessLevel::cases() as $level) {
            $options[$level->value] = $level->getDisplayName();
        }
        return $options;
    }
    
    // 역할 옵션
    public function getRoleOptionsProperty()
    {
        $options = [];
        foreach (ProjectRole::cases() as $role) {
            $options[$role->value] = $role->getDisplayName();
        }
        return $options;
    }
    
    // 역할 표시명 가져오기
    public function getRoleDisplayName($roleValue)
    {
        try {
            $role = ProjectRole::from($roleValue);
            return $role->getDisplayName();
        } catch (\ValueError $e) {
            return $roleValue;
        }
    }
    
    // 역할 색상 클래스 가져오기
    public function getRoleColorClass($roleValue)
    {
        try {
            $role = ProjectRole::from($roleValue);
            return $role->getColorClass();
        } catch (\ValueError $e) {
            return 'bg-gray-100 text-gray-800';
        }
    }
    
    // 접근 권한 표시명 가져오기
    public function getAccessLevelDisplayName($levelValue)
    {
        try {
            $level = PageAccessLevel::from($levelValue);
            return $level->getDisplayName();
        } catch (\ValueError $e) {
            return $levelValue;
        }
    }
    
    // 접근 권한 색상 클래스 가져오기
    public function getAccessLevelColorClass($levelValue)
    {
        try {
            $level = PageAccessLevel::from($levelValue);
            return $level->getColorClass();
        } catch (\ValueError $e) {
            return 'bg-gray-100 text-gray-800';
        }
    }

    public function render()
    {
        return view('livewire.700-livewire-project-settings-permissions');
    }
}