<?php

namespace App\Livewire\Organization\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Organization;
use App\Enums\OrganizationPermission;
use App\Services\PermissionService;
use Illuminate\Support\Collection;

class PermissionManagement extends Component
{
    public $organizationId;
    public $organization;
    public $selectedPermission = null;
    public $permissionMatrix = [];

    public function mount($organizationId = 1)
    {
        $this->organizationId = $organizationId;
        $this->loadData();
    }

    public function loadData()
    {
        $this->organization = Organization::find($this->organizationId);
        $this->permissionMatrix = PermissionService::getPermissionMatrix();
    }

    public function selectPermission($permissionValue)
    {
        $this->selectedPermission = OrganizationPermission::from($permissionValue);
    }

    public function getAvailableFeaturesForSelectedPermission()
    {
        if (!$this->selectedPermission) {
            return [];
        }
        
        return PermissionService::getAvailableFeatures($this->selectedPermission);
    }

    public function getPermissionLevelsProperty()
    {
        return [
            [
                'level' => 0,
                'name' => '없음 (초대됨)',
                'range' => '0-99',
                'permissions' => [OrganizationPermission::INVITED],
                'description' => '조직에 초대되었으나 아직 권한이 부여되지 않음',
                'color' => 'yellow'
            ],
            [
                'level' => 1,
                'name' => '사용자',
                'range' => '100-199',
                'permissions' => [OrganizationPermission::USER, OrganizationPermission::USER_ADVANCED],
                'description' => '기본 사용자 권한, 프로젝트 참여 및 기본 기능 사용',
                'color' => 'blue'
            ],
            [
                'level' => 2,
                'name' => '서비스 매니저',
                'range' => '200-299',
                'permissions' => [OrganizationPermission::SERVICE_MANAGER, OrganizationPermission::SERVICE_MANAGER_SENIOR],
                'description' => '서비스 관리 권한, 프로젝트 관리 및 팀 리딩',
                'color' => 'green'
            ],
            [
                'level' => 3,
                'name' => '조직 관리자',
                'range' => '300-399',
                'permissions' => [OrganizationPermission::ORGANIZATION_ADMIN, OrganizationPermission::ORGANIZATION_ADMIN_SENIOR],
                'description' => '조직 관리 권한, 멤버 관리 및 조직 설정',
                'color' => 'purple'
            ],
            [
                'level' => 4,
                'name' => '조직 소유자',
                'range' => '400-499',
                'permissions' => [OrganizationPermission::ORGANIZATION_OWNER, OrganizationPermission::ORGANIZATION_OWNER_FOUNDER],
                'description' => '조직 소유자, 모든 조직 관리 권한',
                'color' => 'red'
            ],
            [
                'level' => 5,
                'name' => '플랫폼 관리자',
                'range' => '500-599',
                'permissions' => [OrganizationPermission::PLATFORM_ADMIN, OrganizationPermission::PLATFORM_ADMIN_SUPER],
                'description' => '플랫폼 관리자, 시스템 관리 권한',
                'color' => 'gray'
            ]
        ];
    }

    public function render()
    {
        return view('900-page-admin.920-livewire-permission-management', [
            'permissionLevels' => $this->getPermissionLevelsProperty(),
            'availableFeatures' => $this->getAvailableFeaturesForSelectedPermission()
        ]);
    }
}