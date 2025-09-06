<?php

namespace App\Livewire\Organization\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Enums\OrganizationPermission;
use App\Services\PermissionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class MemberManagement extends Component
{
    public $organizationId;
    public $organization;
    public $members = [];
    public $stats = [];
    public $searchTerm = '';
    public $permissionFilter = '';
    public $statusFilter = '';
    
    // 새 멤버 초대 관련 속성
    public $showInviteModal = false;
    public $inviteEmail = '';
    public $invitePermission = 100; // USER 권한

    public function mount($organizationId = 1)
    {
        $this->organizationId = $organizationId;
        try {
            $this->loadData();
        } catch (\Exception $e) {
            logger()->error('MemberManagement mount error: ' . $e->getMessage());
            $this->organization = null;
            $this->members = [];
            $this->stats = ['total' => 0, 'active' => 0, 'pending' => 0, 'admin' => 0];
        }
    }

    public function loadData()
    {
        // 조직 정보 로드
        $this->organization = Organization::find($this->organizationId);
        
        // 실제 DB에서 조직 멤버 로드
        $this->members = $this->getActualMembers();
        
        // 통계 계산
        $this->calculateStats();
    }

    private function getActualMembers()
    {
        try {
            $members = OrganizationMember::with('user')
                ->where('organization_id', $this->organizationId)
                ->orderBy('permission_level', 'desc')
                ->orderBy('joined_at', 'desc')
                ->get();

            logger()->info('Found members for organization ' . $this->organizationId . ': ' . $members->count());

            return $members->map(function ($member) {
                try {
                    $permission = OrganizationPermission::from($member->permission_level);
                    $statusName = match($member->invitation_status) {
                        'pending' => '대기 중',
                        'accepted' => '활성',
                        'declined' => '거절됨',
                        default => '알 수 없음'
                    };

                    $lastActive = $member->invitation_status === 'accepted' ? 
                        ($member->joined_at ? $member->joined_at->diffForHumans() : '알 수 없음') : 
                        '-';

                    return [
                        'id' => $member->id,
                        'user_id' => $member->user->id,
                        'name' => $member->user->name ?? $member->user->email,
                        'email' => $member->user->email,
                        'permission' => [
                            'value' => $permission->value,
                            'label' => $permission->getLabel(),
                            'short_label' => $permission->getShortLabel(),
                            'badge_color' => $permission->getBadgeColor(),
                            'level' => $permission->getLevel(),
                            'level_name' => $permission->getLevelName()
                        ],
                        'status' => $member->invitation_status,
                        'status_name' => $statusName,
                        'joined_at' => $member->joined_at ? $member->joined_at->format('Y.m.d') : 
                                      ($member->invited_at ? $member->invited_at->format('Y.m.d') : '-'),
                        'last_active' => $lastActive,
                        'avatar_color' => $permission->getBadgeColor(),
                        'avatar_initial' => substr($member->user->name ?? $member->user->email, 0, 1)
                    ];
                } catch (\Exception $e) {
                    logger()->error('Error processing member ' . $member->id . ': ' . $e->getMessage());
                    return null;
                }
            })->filter()->values()->toArray();

        } catch (\Exception $e) {
            logger()->error('Error fetching members: ' . $e->getMessage());
            return [];
        }
    }

    private function calculateStats()
    {
        if (!is_array($this->members)) {
            $this->members = [];
        }
        
        $totalMembers = count($this->members);
        $activeMembers = count(array_filter($this->members, fn($member) => $member['status'] === 'active'));
        $pendingMembers = count(array_filter($this->members, fn($member) => $member['status'] === 'pending'));
        $adminMembers = count(array_filter($this->members, fn($member) => 
            $member['permission']['value'] >= OrganizationPermission::ORGANIZATION_ADMIN->value
        ));

        $this->stats = [
            'total' => $totalMembers,
            'active' => $activeMembers,
            'pending' => $pendingMembers,
            'admin' => $adminMembers
        ];
    }

    public function getFilteredMembersProperty()
    {
        $members = $this->members ?? [];

        // 검색어 필터
        if ($this->searchTerm) {
            $members = array_filter($members, function ($member) {
                return stripos($member['name'], $this->searchTerm) !== false ||
                       stripos($member['email'], $this->searchTerm) !== false;
            });
        }

        // 권한 필터
        if ($this->permissionFilter && $this->permissionFilter !== '') {
            $filterLevel = (int) $this->permissionFilter;
            $members = array_filter($members, function ($member) use ($filterLevel) {
                return $member['permission']['level'] === $filterLevel;
            });
        }

        // 상태 필터
        if ($this->statusFilter && $this->statusFilter !== '') {
            $members = array_filter($members, function ($member) {
                return $member['status'] === $this->statusFilter;
            });
        }

        return $members;
    }

    public function changePermission($memberId, $newPermissionValue)
    {
        try {
            $member = OrganizationMember::findOrFail($memberId);
            
            // 조직 소유자의 권한을 낮추려고 하는 경우 방지
            if ($member->permission_level >= OrganizationPermission::ORGANIZATION_OWNER->value && 
                $newPermissionValue < OrganizationPermission::ORGANIZATION_OWNER->value) {
                $this->dispatch('error', [
                    'message' => '조직 소유자의 권한은 낮출 수 없습니다. 소유자 권한은 보호됩니다.'
                ]);
                return;
            }
            
            // 권한 확인 - 현재 사용자가 멤버 관리 권한이 있는지 체크 (실제로는 인증 로직 필요)
            $member->update([
                'permission_level' => $newPermissionValue
            ]);

            $this->loadData(); // 데이터 새로고침
            
            $permission = OrganizationPermission::from($newPermissionValue);
            $this->dispatch('permissionChanged', [
                'memberId' => $memberId,
                'newPermission' => $permission->getLabel(),
                'message' => '권한이 성공적으로 변경되었습니다.'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('error', [
                'message' => '권한 변경 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function removeMember($memberId)
    {
        try {
            $member = OrganizationMember::findOrFail($memberId);
            $userName = $member->user->name ?? $member->user->email;
            
            // 조직 소유자는 삭제할 수 없도록 보호
            if ($member->permission_level >= OrganizationPermission::ORGANIZATION_OWNER->value) {
                $this->dispatch('error', [
                    'message' => '조직 소유자는 삭제할 수 없습니다.'
                ]);
                return;
            }
            
            $member->delete();
            
            // 조직의 멤버 수 업데이트
            $this->organization->update([
                'members_count' => $this->organization->members()->count()
            ]);
            
            $this->loadData(); // 데이터 새로고침
            
            $this->dispatch('memberRemoved', [
                'memberId' => $memberId,
                'message' => "{$userName} 님이 조직에서 제거되었습니다."
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('error', [
                'message' => '멤버 제거 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function resendInvitation($memberId)
    {
        try {
            $member = OrganizationMember::findOrFail($memberId);
            
            // 초대 상태가 아닌 경우 에러
            if ($member->invitation_status !== 'pending') {
                $this->dispatch('error', [
                    'message' => '대기 중인 초대만 재전송할 수 있습니다.'
                ]);
                return;
            }
            
            // 초대 재전송 시간 업데이트
            $member->update([
                'invited_at' => now()
            ]);
            
            // 실제로는 여기에서 이메일 전송 로직이 들어갑니다
            // Mail::to($member->user->email)->send(new OrganizationInvitation($member));
            
            $this->dispatch('invitationResent', [
                'memberId' => $memberId,
                'message' => $member->user->email . '로 초대가 재전송되었습니다.'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('error', [
                'message' => '초대 재전송 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
    }

    public function updatingSearchTerm()
    {
        // 검색어가 변경될 때 실행
    }

    public function openInviteModal()
    {
        $this->showInviteModal = true;
        $this->inviteEmail = '';
        $this->invitePermission = 100;
    }

    public function closeInviteModal()
    {
        $this->showInviteModal = false;
        $this->inviteEmail = '';
        $this->invitePermission = 100;
    }

    public function inviteMember()
    {
        $this->validate([
            'inviteEmail' => 'required|email',
            'invitePermission' => 'required|integer|min:0|max:500'
        ]);

        try {
            // 이미 초대된 사용자인지 확인
            $existingMember = OrganizationMember::where('organization_id', $this->organizationId)
                ->whereHas('user', function($query) {
                    $query->where('email', $this->inviteEmail);
                })
                ->first();

            if ($existingMember) {
                $this->dispatch('error', [
                    'message' => '이미 조직에 초대된 사용자입니다.'
                ]);
                return;
            }

            // 사용자 찾기 또는 생성
            $user = User::firstOrCreate([
                'email' => $this->inviteEmail
            ], [
                'password' => bcrypt('temporary_password'),
                'first_name' => explode('@', $this->inviteEmail)[0],
                'country_code' => 'KR'
            ]);

            // 조직 멤버 추가
            $permission = OrganizationPermission::from($this->invitePermission);
            OrganizationMember::create([
                'organization_id' => $this->organizationId,
                'user_id' => $user->id,
                'permission_level' => $permission->value,
                'invitation_status' => 'pending',
                'invited_at' => now()
            ]);

            // 조직의 멤버 수 업데이트
            $this->organization->update([
                'members_count' => $this->organization->members()->count()
            ]);

            $this->loadData(); // 데이터 새로고침
            $this->closeInviteModal();

            $this->dispatch('memberInvited', [
                'email' => $this->inviteEmail,
                'permission' => $permission->getLabel(),
                'message' => "{$this->inviteEmail}로 초대를 전송했습니다."
            ]);

            // 실제로는 여기에서 이메일 전송 로직이 들어갑니다
            // Mail::to($user->email)->send(new OrganizationInvitation($member));

        } catch (\Exception $e) {
            $this->dispatch('error', [
                'message' => '멤버 초대 중 오류가 발생했습니다: ' . $e->getMessage()
            ]);
        }
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
        return view('900-page-platform-admin.910-livewire-member-management', [
            'filteredMembers' => $this->getFilteredMembersProperty(),
            'permissionLevels' => $this->getPermissionLevelsProperty()
        ]);
    }
}