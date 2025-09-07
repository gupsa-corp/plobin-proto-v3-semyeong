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
            $this->totalOrganizations = Organization::count();
            $this->totalUsers = User::count();
            $this->totalProjects = Project::count();
            
            // 시스템 상태 체크 (간단한 헬스 체크)
            $this->systemStatus = $this->checkSystemHealth();
        } catch (\Exception $e) {
            // 오류 발생시 0으로 설정
            $this->totalOrganizations = 0;
            $this->totalUsers = 0;
            $this->totalProjects = 0;
            $this->systemStatus = '오류';
        }
    }

    private function checkSystemHealth()
    {
        try {
            // 기본적인 DB 연결 및 모델 접근 테스트
            Organization::exists();
            User::exists();
            Project::exists();
            
            return '정상';
        } catch (\Exception $e) {
            return '경고';
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