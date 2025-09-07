<?php

namespace App\Livewire\PlatformAdmin\Dashboard;

use App\Models\Organization;
use Livewire\Component;

class OrgStats extends Component
{
    public $totalOrganizations = 0;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        try {
            $this->totalOrganizations = Organization::count();
        } catch (\Exception $e) {
            // 오류 발생시 0으로 설정
            $this->totalOrganizations = 0;
        }
    }

    public function refreshStats()
    {
        $this->loadStats();
        $this->dispatch('stats-updated');
    }

    public function render()
    {
        return view('900-page-platform-admin.901-page-dashboard.301-1-livewire-block-org-stats');
    }
}