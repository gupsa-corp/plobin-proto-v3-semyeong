<?php

namespace App\Livewire\Organization;

use Livewire\Component;

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
        // TODO: 실제 조직 데이터 로드 로직 구현
        // 현재는 빈 배열로 설정
        $this->organizations = [];
        $this->isLoading = false;
    }

    public function render()
    {
        return view('300-page-service.306-page-organizations-list.300-livewire-organization-list');
    }
}