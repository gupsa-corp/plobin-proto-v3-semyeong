<?php

namespace App\Livewire\Organization\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Collection;

class MemberManagement extends Component
{
    public $organizationId;
    public $organization;
    public $members;
    public $stats = [];
    public $searchTerm = '';
    public $roleFilter = '';
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
                'role' => 'admin',
                'role_name' => '관리자',
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.01.15',
                'last_active' => '2시간 전',
                'avatar_color' => 'blue',
                'avatar_initial' => '김'
            ],
            [
                'id' => 2,
                'name' => '이영희',
                'email' => 'lee@techcorp.com',
                'role' => 'member',
                'role_name' => '멤버',
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.02.03',
                'last_active' => '1일 전',
                'avatar_color' => 'green',
                'avatar_initial' => '이'
            ],
            [
                'id' => 3,
                'name' => '박민수',
                'email' => 'park@techcorp.com',
                'role' => 'viewer',
                'role_name' => '뷰어',
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
                'role' => 'admin',
                'role_name' => '관리자',
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.01.20',
                'last_active' => '3시간 전',
                'avatar_color' => 'purple',
                'avatar_initial' => '정'
            ],
            [
                'id' => 5,
                'name' => '홍길동',
                'email' => 'hong@techcorp.com',
                'role' => 'project_manager',
                'role_name' => '프로젝트 매니저',
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.02.15',
                'last_active' => '30분 전',
                'avatar_color' => 'indigo',
                'avatar_initial' => '홍'
            ],
            [
                'id' => 6,
                'name' => '김영수',
                'email' => 'kimys@techcorp.com',
                'role' => 'member',
                'role_name' => '멤버',
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.02.20',
                'last_active' => '5시간 전',
                'avatar_color' => 'red',
                'avatar_initial' => '김'
            ],
            [
                'id' => 7,
                'name' => '이민정',
                'email' => 'leemj@techcorp.com',
                'role' => 'member',
                'role_name' => '멤버',
                'status' => 'active',
                'status_name' => '활성',
                'joined_at' => '2024.03.01',
                'last_active' => '1시간 전',
                'avatar_color' => 'pink',
                'avatar_initial' => '이'
            ],
            [
                'id' => 8,
                'name' => '최상훈',
                'email' => 'choi@techcorp.com',
                'role' => 'viewer',
                'role_name' => '뷰어',
                'status' => 'pending',
                'status_name' => '대기 중',
                'joined_at' => '2024.03.12',
                'last_active' => '-',
                'avatar_color' => 'gray',
                'avatar_initial' => '최'
            ]
        ]);
    }

    private function calculateStats()
    {
        $totalMembers = $this->members->count();
        $activeMembers = $this->members->where('status', 'active')->count();
        $pendingMembers = $this->members->where('status', 'pending')->count();
        $adminMembers = $this->members->where('role', 'admin')->count();

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

        // 역할 필터
        if ($this->roleFilter && $this->roleFilter !== 'all') {
            $members = $members->where('role', $this->roleFilter);
        }

        // 상태 필터
        if ($this->statusFilter && $this->statusFilter !== 'all') {
            $members = $members->where('status', $this->statusFilter);
        }

        return $members;
    }

    public function updatingSearchTerm()
    {
        // 검색어가 변경될 때 실행
    }

    public function render()
    {
        return view('livewire.organization.admin.member-management', [
            'filteredMembers' => $this->getFilteredMembersProperty()
        ]);
    }
}