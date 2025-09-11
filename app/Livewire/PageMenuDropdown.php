<?php

namespace App\Livewire;

use Livewire\Component;

class PageMenuDropdown extends Component
{
    public $page;

    public function mount($page)
    {
        $this->page = $page;
    }

    public function updatePageTitle($pageId, $newTitle)
    {
        $this->dispatch('updatePageTitle', pageId: $pageId, newTitle: $newTitle);
    }

    public function addChildPage($pageId)
    {
        $this->dispatch('addChildPage', pageId: $pageId);
    }

    public function deletePage($pageId)
    {
        $this->dispatch('deletePage', pageId: $pageId);
    }

    public function openPageSettings($pageId)
    {
        $orgId = request()->route('id');
        $projectId = request()->route('projectId');
        
        return redirect()->route('project.dashboard.page.settings', [
            'id' => $orgId,
            'projectId' => $projectId,
            'pageId' => $pageId
        ]);
    }

    public function render()
    {
        return view('livewire.page-menu-dropdown');
    }
}
