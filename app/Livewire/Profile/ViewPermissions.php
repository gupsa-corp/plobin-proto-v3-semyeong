<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewPermissions extends Component
{
    public $user;
    public $organizationsPermissions = [];

    public function mount()
    {
        $this->user = Auth::user();

        // 사용자가 로그인하지 않은 경우 로그인 페이지로 리디렉션
        if (!$this->user) {
            return redirect('/login');
        }

        // 조직별 권한 정보 로드
        $this->loadOrganizationPermissions();
    }

    public function loadOrganizationPermissions()
    {
        $organizations = \App\Models\Organization::whereHas('members', function($query) {
            $query->where('user_id', $this->user->id)
                  ->where('invitation_status', 'accepted');
        })->with(['members' => function($query) {
            $query->where('user_id', $this->user->id);
        }])->get();

        $this->organizationsPermissions = $organizations->map(function($org) {
            $member = $org->members->first();
            return [
                'organization' => $org,
                'role' => $member->role_name ?? 'member',
                'joined_at' => $member->created_at ?? null,
                'permissions' => $this->getPermissionsByRole($member->role_name ?? 'member')
            ];
        })->toArray();
    }

    private function getPermissionsByRole($role)
    {
        $permissions = [
            'organization_owner' => [
                '조직 관리' => ['조직 설정 변경', '조직 삭제', '멤버 초대 및 관리', '권한 관리', '결제 관리'],
                '프로젝트' => ['모든 프로젝트 접근', '프로젝트 생성/삭제', '프로젝트 설정 관리'],
                '데이터' => ['모든 데이터 접근', '데이터 내보내기', '백업 관리']
            ],
            'admin' => [
                '조직 관리' => ['멤버 관리', '일부 권한 관리'],
                '프로젝트' => ['대부분 프로젝트 접근', '프로젝트 생성'],
                '데이터' => ['대부분 데이터 접근']
            ],
            'member' => [
                '조직 관리' => ['기본 정보 확인'],
                '프로젝트' => ['할당된 프로젝트 접근', '프로젝트 내 작업'],
                '데이터' => ['할당된 데이터 접근']
            ]
        ];

        return $permissions[$role] ?? $permissions['member'];
    }

    public function render()
    {
        return view('livewire.profile.500-view-permissions');
    }
}