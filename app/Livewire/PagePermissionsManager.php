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
    public $allowedEmails = [];
    public $emailInput = '';
    
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
        
        // allowed_roles 데이터 파싱 (기존 사용자 ID와 이메일 분리)
        $allowedData = $this->currentPage->allowed_roles ?: [];
        $this->allowedRoles = [];
        $this->allowedEmails = [];
        
        foreach ($allowedData as $item) {
            if (is_numeric($item)) {
                // 기존 사용자 ID
                $this->allowedRoles[] = $item;
            } else if (filter_var($item, FILTER_VALIDATE_EMAIL)) {
                // 이메일 주소
                $this->allowedEmails[] = $item;
            }
        }
    }
    
    public function loadOrganizationMembers()
    {
        $organizationId = $this->currentPage->project->organization->id;
        
        $this->organizationMembers = OrganizationMember::with(['user'])
            ->where('organization_id', $organizationId)
            ->where('invitation_status', 'accepted')
            ->get();
    }
    
    public function addEmail()
    {
        $this->validate([
            'emailInput' => 'required|email'
        ], [
            'emailInput.required' => '이메일을 입력해주세요.',
            'emailInput.email' => '올바른 이메일 주소를 입력해주세요.'
        ]);
        
        // 이메일이 이미 추가된 경우 체크
        if (in_array($this->emailInput, $this->allowedEmails)) {
            session()->flash('error', '이미 추가된 이메일입니다.');
            return;
        }
        
        // 프로젝트 매니저(PM)인지 확인 - 자동으로 추가
        $projectOwnerId = $this->currentPage->project->user_id;
        $pmUser = User::find($projectOwnerId);
        
        // PM 이메일 자동 추가 (아직 없다면)
        if ($pmUser && !in_array($pmUser->email, $this->allowedEmails)) {
            $this->allowedEmails[] = $pmUser->email;
        }
        
        // 입력된 이메일 추가
        $this->allowedEmails[] = $this->emailInput;
        $this->emailInput = '';
        
        session()->flash('message', 'PM과 추가된 사용자만 볼 수 있도록 이메일이 추가되었습니다.');
    }
    
    public function removeEmail($email)
    {
        $this->allowedEmails = array_values(array_filter($this->allowedEmails, function($item) use ($email) {
            return $item !== $email;
        }));
        
        session()->flash('message', '이메일이 삭제되었습니다.');
    }
    
    public function updatePermissions()
    {
        $this->validate([
            'accessLevel' => 'required|in:public,member,contributor,moderator,admin,owner,custom',
            'allowedRoles' => 'array',
            'allowedEmails' => 'array'
        ]);
        
        // 변경 전 상태 저장
        $oldAccessLevel = $this->currentPage->access_level ?: 'public';
        $oldAllowedRoles = $this->currentPage->allowed_roles ?: [];
        
        // Custom 모드일 때 사용자 ID와 이메일을 합쳐서 저장
        $combinedRoles = [];
        if ($this->accessLevel === 'custom') {
            // PM 이메일 자동 추가
            $projectOwnerId = $this->currentPage->project->user_id;
            $pmUser = User::find($projectOwnerId);
            
            if ($pmUser && !empty($this->allowedEmails)) {
                // PM 이메일이 목록에 없으면 자동 추가
                if (!in_array($pmUser->email, $this->allowedEmails)) {
                    $this->allowedEmails[] = $pmUser->email;
                }
            }
            
            $combinedRoles = array_merge($this->allowedRoles, $this->allowedEmails);
        }
        
        // 페이지 업데이트
        $this->currentPage->update([
            'access_level' => $this->accessLevel,
            'allowed_roles' => $this->accessLevel === 'custom' ? $combinedRoles : null
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
            'user_id' => auth()->id(),
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
            'member' => '멤버 이상',
            'contributor' => '기여자 이상',
            'moderator' => '중간관리자 이상',
            'admin' => '관리자 이상',
            'owner' => '소유자만',
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
            'member' => '멤버 이상',
            'contributor' => '기여자 이상',
            'moderator' => '중간관리자 이상',
            'admin' => '관리자 이상',
            'owner' => '소유자만',
            'custom' => '사용자 지정'
        ];
        
        return $labels[$this->accessLevel] ?? '알 수 없음';
    }
    
    public function getAccessLevelDescriptionProperty()
    {
        $descriptions = [
            'public' => '누구나 이 페이지를 볼 수 있습니다.',
            'member' => '프로젝트 멤버 이상만 볼 수 있습니다.',
            'contributor' => '기여자 권한 이상을 가진 사용자만 볼 수 있습니다.',
            'moderator' => '중간관리자 권한 이상을 가진 사용자만 볼 수 있습니다.',
            'admin' => '관리자 권한 이상을 가진 사용자만 볼 수 있습니다.',
            'owner' => '프로젝트 소유자만 볼 수 있습니다.',
            'custom' => '선택된 특정 사용자만 볼 수 있습니다.'
        ];
        
        return $descriptions[$this->accessLevel] ?? '';
    }

    public function render()
    {
        return view('page-permissions-manager');
    }
}