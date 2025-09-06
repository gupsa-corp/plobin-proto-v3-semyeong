<?php

namespace App\Livewire\Organization\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Organization;
use App\Enums\OrganizationPermission;
use App\Services\PermissionService;
use Illuminate\Support\Collection;

class MemberManagement extends Component
{
    public $organizationId;
    public $organization;
    public $members;
    public $stats = [];
    public $searchTerm = '';
    public $permissionFilter = '';
    public $statusFilter = '';

    public function mount($organizationId = 1)
    {
        $this->organizationId = $organizationId;
        $this->loadData();
    }

    public function loadData()
    {
        // 조직 정보 로드
        $this->organization = Organization::find($this->organizationId);
        
        // 실제 DB에 조직 멤버 관계가 있다면 그것을 사용하고, 
        // 없다면 샘플 데이터를 생성
        $this->members = $this->generateSampleMembers();
        
        // 통계 계산
        $this->calculateStats();
    }

    private function generateSampleMembers()
    {
        return collect([
            [
                'id' => 1,
                'name' => '김철수',
                'email' => 'kim@techcorp.com',
                'permission' => OrganizationPermission::ORGANIZATION_OWNER,
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.01.15',
                'last_active' => '2시간 전',
                'avatar_color' => 'red',
                'avatar_initial' => '김'
            ],
            [
                'id' => 2,
                'name' => '이영희',
                'email' => 'lee@techcorp.com',
                'permission' => OrganizationPermission::ORGANIZATION_ADMIN,
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.02.03',
                'last_active' => '1일 전',
                'avatar_color' => 'purple',
                'avatar_initial' => '이'
            ],
            [
                'id' => 3,
                'name' => '박민수',
                'email' => 'park@techcorp.com',
                'permission' => OrganizationPermission::INVITED,
                'status' => 'pending',
                'status_name' => '대기 중',
                'joined_at' => '2024.03.10',
                'last_active' => '-',
                'avatar_color' => 'yellow',
                'avatar_initial' => '박'
            ],
            [
                'id' => 4,
                'name' => '정수현',
                'email' => 'jung@techcorp.com',
                'permission' => OrganizationPermission::SERVICE_MANAGER,
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.01.20',
                'last_active' => '3시간 전',
                'avatar_color' => 'green',
                'avatar_initial' => '정'
            ],
            [
                'id' => 5,
                'name' => '홍길동',
                'email' => 'hong@techcorp.com',
                'permission' => OrganizationPermission::SERVICE_MANAGER_SENIOR,
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.02.15',
                'last_active' => '30분 전',
                'avatar_color' => 'green',
                'avatar_initial' => '홍'
            ],
            [
                'id' => 6,
                'name' => '김영수',
                'email' => 'kimys@techcorp.com',
                'permission' => OrganizationPermission::USER,
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.02.20',
                'last_active' => '5시간 전',
                'avatar_color' => 'blue',
                'avatar_initial' => '김'
            ],
            [
                'id' => 7,
                'name' => '이민정',
                'email' => 'leemj@techcorp.com',
                'permission' => OrganizationPermission::USER_ADVANCED,
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.03.01',
                'last_active' => '1시간 전',
                'avatar_color' => 'blue',
                'avatar_initial' => '이'
            ],
            [
                'id' => 8,
                'name' => '최상훈',
                'email' => 'choi@techcorp.com',
                'permission' => OrganizationPermission::INVITED,
                'status' => 'pending',
                'status_name' => '대기 중',
                'joined_at' => '2024.03.12',
                'last_active' => '-',
                'avatar_color' => 'yellow',
                'avatar_initial' => '최'
            ],
            [
                'id' => 9,
                'name' => '윤지은',
                'email' => 'yoon@techcorp.com',
                'permission' => OrganizationPermission::PLATFORM_ADMIN,
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.01.01',
                'last_active' => '10분 전',
                'avatar_color' => 'gray',
                'avatar_initial' => '윤'
            ],
            [
                'id' => 10,
                'name' => '송현우',
                'email' => 'song@techcorp.com',
                'permission' => OrganizationPermission::ORGANIZATION_OWNER_FOUNDER,
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2023.12.01',
                'last_active' => '방금 전',
                'avatar_color' => 'red',
                'avatar_initial' => '송'
            ]
        ]);
    }

    private function calculateStats()
    {
        $totalMembers = $this->members->count();
        $activeMembers = $this->members->where('status', 'active')->count();
        $pendingMembers = $this->members->where('status', 'pending')->count();
        $adminMembers = $this->members->filter(function ($member) {
            return $member['permission']->value >= OrganizationPermission::ORGANIZATION_ADMIN->value;
        })->count();

        $this->stats = [
            'total' => $totalMembers,
            'active' => $activeMembers,
            'pending' => $pendingMembers,
            'admin' => $adminMembers
        ];
    }

    public function getFilteredMembersProperty()
    {
        $members = $this->members;

        // 검색어 필터
        if ($this->searchTerm) {
            $members = $members->filter(function ($member) {
                return stripos($member['name'], $this->searchTerm) !== false ||
                       stripos($member['email'], $this->searchTerm) !== false;
            });
        }

        // 권한 필터
        if ($this->permissionFilter && $this->permissionFilter !== '') {
            $filterLevel = (int) $this->permissionFilter;
            $members = $members->filter(function ($member) use ($filterLevel) {
                return $member['permission']->getLevel() === $filterLevel;
            });
        }

        // 상태 필터
        if ($this->statusFilter && $this->statusFilter !== '') {
            $members = $members->where('status', $this->statusFilter);
        }

        return $members;
    }

    public function changePermission($memberId, $newPermissionValue)
    {
        // 실제 구현에서는 데이터베이스 업데이트 로직이 들어갑니다
        // 현재는 샘플 데이터이므로 알림만 표시
        $this->dispatch('permissionChanged', [
            'memberId' => $memberId,
            'newPermission' => $newPermissionValue
        ]);
    }

    public function removeMember($memberId)
    {
        // 실제 구현에서는 멤버 제거 로직이 들어갑니다
        $this->dispatch('memberRemoved', ['memberId' => $memberId]);
    }

    public function resendInvitation($memberId)
    {
        // 실제 구현에서는 초대 재전송 로직이 들어갑니다
        $this->dispatch('invitationResent', ['memberId' => $memberId]);
    }

    public function updatingSearchTerm()
    {
        // 검색어가 변경될 때 실행
    }

    public function getPermissionLevelsProperty()
    {
        return [
            0 => '없음 (초대됨)',
            1 => '사용자',
            2 => '서비스 매니저',
            3 => '조직 관리자',
            4 => '조직 소유자',
            5 => '플랫폼 관리자'
        ];
    }

    public function render()
    {
        return view('livewire.organization.admin.member-management', [
            'filteredMembers' => $this->getFilteredMembersProperty(),
            'permissionLevels' => $this->getPermissionLevelsProperty()
        ]);
    }
}