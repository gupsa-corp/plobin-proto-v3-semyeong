<?php

namespace App\Livewire\PlatformAdmin;

use Livewire\Component;
use App\Models\Organization;

class OrganizationSelector extends Component
{
    public $selectedScope = 'platform';
    public $selectedOrganization = null;
    public $organizations = [];
    
    public function mount()
    {
        $this->loadOrganizations();
    }

    public function rendered()
    {
        // 컴포넌트가 렌더링된 후 이벤트 발생 (JavaScript가 준비된 후)
        $this->dispatch('scope-changed', [
            'scope' => $this->selectedScope,
            'organizationId' => $this->selectedOrganization
        ]);
    }
    
    public function loadOrganizations()
    {
        $this->organizations = Organization::select('id', 'name')
            ->orderBy('name')
            ->get()
            ->toArray();
    }
    
    public function updatedSelectedScope($value)
    {
        if ($value === 'platform') {
            $this->selectedOrganization = null;
        }
        
        $this->dispatch('scope-changed', [
            'scope' => $value,
            'organizationId' => $this->selectedOrganization
        ]);
    }
    
    public function updatedSelectedOrganization($value)
    {
        $this->dispatch('scope-changed', [
            'scope' => $this->selectedScope,
            'organizationId' => $value
        ]);
    }

    public function render()
    {
        return view('livewire.platform-admin.organization-selector');
    }
}