<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProjectPage;

class ProjectPageTabs extends Component
{
    public $orgId;
    public $projectId;
    public $currentPageId;
    public $activePageId;
    public $subPages = [];

    public function mount($orgId, $projectId, $currentPageId = null)
    {
        $this->orgId = $orgId;
        $this->projectId = $projectId;
        $this->currentPageId = $currentPageId;
        $this->activePageId = $currentPageId;
        
        $this->loadSubPages();
    }

    public function loadSubPages()
    {
        if (!$this->currentPageId) {
            return;
        }

        // 현재 페이지의 하위 페이지들 (형제 페이지들) 가져오기
        $this->subPages = ProjectPage::where('project_id', $this->projectId)
            ->where('parent_id', $this->currentPageId)
            ->orderBy('sort_order')
            ->get()
            ->toArray();
    }

    public function navigateToPage($pageId)
    {
        return redirect()->route('project.dashboard.page', [
            'id' => $this->orgId,
            'projectId' => $this->projectId,
            'pageId' => $pageId
        ]);
    }

    public function render()
    {
        return view('project-page-tabs');
    }
}