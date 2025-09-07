<?php

namespace App\Livewire\PlatformAdmin\Dashboard;

use Livewire\Component;
use App\Models\Organization;
use App\Models\User;
use App\Models\Project;

class SystemStats extends Component
{
    public $totalOrganizations = 0;
    public $totalUsers = 0;
    public $totalProjects = 0;
    public $systemStatus = '정상';

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        try {
            // TODO: 실제 모델이 존재하는지 확인 후 구현
            // 현재는 더미 데이터 사용
            $this->totalOrganizations = 12; // Organization::count();
            $this->totalUsers = 147; // User::count();
            $this->totalProjects = 89; // Project::count();
            $this->systemStatus = '정상';
        } catch (\Exception $e) {
            // 모델이 없는 경우 더미 데이터 유지
            $this->totalOrganizations = 12;
            $this->totalUsers = 147;
            $this->totalProjects = 89;
            $this->systemStatus = '정상';
        }
    }

    public function refreshStats()
    {
        $this->loadStats();
        $this->dispatch('stats-updated');
    }

    public function render()
    {
        return view('900-page-platform-admin.901-page-dashboard.300-livewire-block-system-stats');
    }
}