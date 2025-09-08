<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class OrganizationSelector extends Component
{
    public $organizations = [];
    public $currentOrgId = null;
    public $searchTerm = '';
    public $isDropdownOpen = false;

    public function mount()
    {
        $this->loadUserOrganizations();
        $this->extractCurrentOrgId();
    }

    protected function loadUserOrganizations()
    {
        $user = Auth::user();
        if (!$user) {
            $this->organizations = collect();
            return;
        }

        // 사용자가 속한 조직들 중 invitation_status가 'accepted'인 것만 가져옴
        $this->organizations = $user->organizations()
            ->wherePivot('invitation_status', 'accepted')
            ->get();
    }

    protected function extractCurrentOrgId()
    {
        $currentPath = request()->getRequestUri();
        if (preg_match('/\/organizations\/(\d+)/', $currentPath, $matches)) {
            $this->currentOrgId = (int) $matches[1];
        }
    }

    public function toggleDropdown()
    {
        $this->isDropdownOpen = !$this->isDropdownOpen;
        if ($this->isDropdownOpen) {
            $this->searchTerm = '';
        }
    }

    public function selectOrganization($orgId)
    {
        if ($orgId !== $this->currentOrgId) {
            $currentPath = request()->path();
            
            if (str_contains($currentPath, 'organizations/')) {
                // 현재 URL의 조직 ID를 새로운 조직 ID로 변경
                $newPath = preg_replace('/organizations\/\d+/', "organizations/{$orgId}", $currentPath);
            } else {
                // 조직 페이지가 아닌 경우 조직 대시보드로 이동
                $newPath = "organizations/{$orgId}/dashboard";
            }
            
            // 전체 URL로 리다이렉트 (쿼리 파라미터 유지)
            $fullUrl = url($newPath);
            if (request()->getQueryString()) {
                $fullUrl .= '?' . request()->getQueryString();
            }
            
            return redirect($fullUrl);
        }
        
        $this->isDropdownOpen = false;
    }

    public function createOrganization()
    {
        return redirect('/organizations/create');
    }

    public function getFilteredOrganizationsProperty()
    {
        if (empty($this->searchTerm)) {
            return $this->organizations;
        }

        return $this->organizations->filter(function ($org) {
            return str_contains(strtolower($org->name), strtolower($this->searchTerm)) ||
                   str_contains((string) $org->id, $this->searchTerm);
        });
    }

    public function getCurrentOrganizationProperty()
    {
        if (!$this->currentOrgId) {
            return null;
        }

        return $this->organizations->firstWhere('id', $this->currentOrgId);
    }

    public function render()
    {
        return view('livewire.organization-selector');
    }
}