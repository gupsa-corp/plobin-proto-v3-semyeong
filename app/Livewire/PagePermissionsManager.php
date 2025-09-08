<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProjectPage;
use App\Models\User;
use App\Models\OrganizationMember;
use App\Models\ProjectPageDeploymentLog;
use App\Enums\PageAccessLevel;
use App\Enums\ProjectRole;

class PagePermissionsManager extends Component
{
    public $pageId;
    public $currentPage;
    public $accessLevel;
    public $allowedRoles = [];
    
    // 조직 멤버들
    public $organizationMembers = [];
    
    public function mount($pageId)
    {
        $this->pageId = $pageId;
        $this->loadPage();
        $this->loadOrganizationMembers();
    }
    
    public function loadPage()
    {
        $this->currentPage = ProjectPage::with('project.organization')->findOrFail($this->pageId);
        $this->accessLevel = $this->currentPage->access_level ?: 'public';
        $this->allowedRoles = $this->currentPage->allowed_roles ?: [];
    }
    
    public function loadOrganizationMembers()
    {
        $organizationId = $this->currentPage->project->organization->id;
        
        $this->organizationMembers = OrganizationMember::with(['user'])
            ->where('organization_id', $organizationId)
            ->where('invitation_status', 'accepted')
            ->get();
    }
    
    public function updatePermissions()
    {
        $this->validate([
            'accessLevel' => 'required|in:public,members_only,editors_only,admins_only,custom',
            'allowedRoles' => 'array'
        ]);
        
        // 변경 전 상태 저장
        $oldAccessLevel = $this->currentPage->access_level ?: 'public';
        $oldAllowedRoles = $this->currentPage->allowed_roles ?: [];
        
        // 페이지 업데이트
        $this->currentPage->update([
            'access_level' => $this->accessLevel,
            'allowed_roles' => $this->accessLevel === 'custom' ? $this->allowedRoles : null
        ]);
        
        // 변경 로그 생성 (권한이 실제로 변경된 경우에만)
        if ($oldAccessLevel !== $this->accessLevel || $oldAllowedRoles !== ($this->accessLevel === 'custom' ? $this->allowedRoles : [])) {
            $this->createPermissionChangeLog($oldAccessLevel, $this->accessLevel, $oldAllowedRoles, $this->allowedRoles);
        }
        
        session()->flash('message', '보기 권한이 성공적으로 업데이트되었습니다.');
        $this->loadPage();
    }
    
    private function createPermissionChangeLog($oldAccessLevel, $newAccessLevel, $oldAllowedRoles, $newAllowedRoles)
    {
        ProjectPageDeploymentLog::create([
            'project_page_id' => $this->pageId,
            'user_id' => 1, // 현재는 하드코딩, 실제로는 auth()->id() 사용
            'change_type' => 'permission',
            'from_status' => $oldAccessLevel,
            'to_status' => $newAccessLevel,
            'reason' => $this->generatePermissionChangeReason($oldAccessLevel, $newAccessLevel),
            'change_data' => [
                'old_access_level' => $oldAccessLevel,
                'new_access_level' => $newAccessLevel,
                'old_allowed_roles' => $oldAllowedRoles,
                'new_allowed_roles' => $newAccessLevel === 'custom' ? $newAllowedRoles : []
            ]
        ]);
    }
    
    private function generatePermissionChangeReason($oldAccessLevel, $newAccessLevel)
    {
        $labels = [
            'public' => '모든 사용자',
            'members_only' => '조직 멤버만',
            'editors_only' => '편집자 이상',
            'admins_only' => '관리자만',
            'custom' => '사용자 지정'
        ];
        
        $oldLabel = $labels[$oldAccessLevel] ?? $oldAccessLevel;
        $newLabel = $labels[$newAccessLevel] ?? $newAccessLevel;
        
        return "페이지 접근 권한을 '{$oldLabel}'에서 '{$newLabel}'로 변경";
    }
    
    public function getAccessLevelLabelProperty()
    {
        $labels = [
            'public' => '모든 사용자',
            'members_only' => '조직 멤버만',
            'editors_only' => '편집자 이상',
            'admins_only' => '관리자만',
            'custom' => '사용자 지정'
        ];
        
        return $labels[$this->accessLevel] ?? '알 수 없음';
    }
    
    public function getAccessLevelDescriptionProperty()
    {
        $descriptions = [
            'public' => '누구나 이 페이지를 볼 수 있습니다.',
            'members_only' => '조직에 속한 멤버만 볼 수 있습니다.',
            'editors_only' => '편집 권한 이상을 가진 사용자만 볼 수 있습니다.',
            'admins_only' => '관리자 권한을 가진 사용자만 볼 수 있습니다.',
            'custom' => '선택된 특정 사용자만 볼 수 있습니다.'
        ];
        
        return $descriptions[$this->accessLevel] ?? '';
    }

    public function render()
    {
        return view('page-permissions-manager');
    }
}