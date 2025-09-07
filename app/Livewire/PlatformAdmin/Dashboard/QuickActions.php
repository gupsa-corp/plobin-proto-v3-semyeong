<?php

namespace App\Livewire\PlatformAdmin\Dashboard;

use Livewire\Component;

class QuickActions extends Component
{
    public $actions = [];

    public function mount()
    {
        $this->loadActions();
    }

    public function loadActions()
    {
        $this->actions = [
            [
                'name' => '조직 관리',
                'route' => 'platform.admin.organizations',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'text-gray-500',
                'description' => '조직 생성, 수정, 삭제 및 멤버 관리'
            ],
            [
                'name' => '사용자 관리',
                'route' => 'platform.admin.users',
                'icon' => 'M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z',
                'color' => 'text-gray-500',
                'description' => '사용자 계정 관리 및 권한 설정'
            ],
            [
                'name' => '권한 관리',
                'route' => 'platform.admin.permissions',
                'icon' => 'M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z',
                'color' => 'text-gray-500',
                'description' => '역할별 권한 설정 및 동적 규칙 관리'
            ],
            [
                'name' => '시스템 설정',
                'route' => 'platform.admin.system-settings',
                'icon' => 'M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z',
                'color' => 'text-gray-500',
                'description' => '플랫폼 전반 설정 및 보안 관리'
            ]
        ];
    }

    public function navigateTo($routeName)
    {
        return redirect()->route($routeName);
    }

    public function render()
    {
        return view('900-page-platform-admin.901-page-dashboard.300-livewire-block-quick-actions');
    }
}