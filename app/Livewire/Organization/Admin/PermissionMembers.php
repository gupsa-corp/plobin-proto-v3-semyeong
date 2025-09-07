<?php

namespace App\Livewire\Organization\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Organization;
use App\Services\DynamicPermissionService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;

class PermissionMembers extends Component
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
     * 역할 변경
     */
    public function changeMemberRole($memberId, $roleName)
    {
        try {
            $user = User::find($memberId);
            if (!$user) {
                throw new \Exception('사용자를 찾을 수 없습니다.');
            }

            // 기존 역할 제거
            $user->syncRoles([$roleName]);
            
            $this->loadData();
            
            session()->flash('success', '멤버 역할이 성공적으로 변경되었습니다.');
        } catch (\Exception $e) {
            session()->flash('error', '역할 변경 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    public function getRolesProperty()
    {
        return Role::all()->map(function ($role) {
            $permissions = $role->permissions->pluck('name')->toArray();
            $info = $this->getRoleDisplayInfo($role->name);

            return [
                'name' => $role->name,
                'label' => $info['label'],
                'description' => $info['description'],
                'color' => $info['color'],
                'level' => $info['level'],
                'permissions' => $permissions,
                'permission_count' => count($permissions)
            ];
        })->sortBy('level')->values()->toArray();
    }

    /**
     * 역할별 표시 정보 반환
     */
    private function getRoleDisplayInfo($roleName)
    {
        return match($roleName) {
            'user' => [
                'label' => '사용자',
                'description' => '기본 사용자 권한, 프로젝트 참여 및 기본 기능 사용',
                'color' => 'blue',
                'level' => 1
            ],
            'service_manager' => [
                'label' => '서비스 매니저',
                'description' => '서비스 관리 권한, 프로젝트 관리 및 팀 리딩',
                'color' => 'green',
                'level' => 2
            ],
            'organization_admin' => [
                'label' => '조직 관리자',
                'description' => '조직 관리 권한, 멤버 관리 및 조직 설정',
                'color' => 'purple',
                'level' => 3
            ],
            'organization_owner' => [
                'label' => '조직 소유자',
                'description' => '조직 소유자, 모든 조직 관리 권한',
                'color' => 'red',
                'level' => 4
            ],
            'platform_admin' => [
                'label' => '플랫폼 관리자',
                'description' => '플랫폼 관리자, 시스템 관리 권한',
                'color' => 'gray',
                'level' => 5
            ],
            default => [
                'label' => $roleName,
                'description' => '사용자 정의 역할',
                'color' => 'indigo',
                'level' => 999
            ]
        };
    }

    public function render()
    {
        return view('800-page-organization-admin.807-page-permissions-management.000-index', [
            'members' => $this->getMembersProperty(),
            'roles' => $this->getRolesProperty()
        ]);
    }
}