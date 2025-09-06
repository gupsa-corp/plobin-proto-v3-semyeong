<?php

namespace App\Livewire\Organization;

use Livewire\Component;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class OrganizationList extends Component
{
    public $organizations = [];
    public $isLoading = true;

    protected $listeners = ['organizationCreated' => 'loadOrganizations'];

    public function mount()
    {
        $this->loadOrganizations();
    }

    public function loadOrganizations()
    {
        // 현재 사용자가 생성한 조직들을 로드
        $this->organizations = Organization::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        $this->isLoading = false;
    }

    public function render()
    {
        return view('300-page-service.306-page-organizations-list.300-livewire-organization-list');
    }
}