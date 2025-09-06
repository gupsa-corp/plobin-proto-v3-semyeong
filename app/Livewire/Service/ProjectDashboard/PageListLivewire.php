<?php

namespace App\Livewire\Service\ProjectDashboard;

use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectPage;
use Livewire\Component;
use Livewire\Attributes\On;

class PageListLivewire extends Component
{
    public $projectId;
    public $pages = [];
    public $currentPage = null;
    public $isLoading = false;

    protected $listeners = ['pageCreated' => 'loadPages'];

    public function mount($projectId, $currentPageId = null)
    {
        $this->projectId = $projectId;
        $this->loadPages();
        $this->setCurrentPageFromUrl($currentPageId);
    }
    
    private function setCurrentPageFromUrl($currentPageId = null)
    {
        if ($currentPageId) {
            // 동적 페이지 찾기
            $page = collect($this->pages)->firstWhere('id', $currentPageId);
            if ($page) {
                $this->currentPage = [
                    'id' => $page['id'],
                    'title' => $page['title'],
                    'description' => $page['content'] ?? $page['title'],
                    'breadcrumb' => $page['title']
                ];
            }
        } else {
            // 첫 번째 페이지를 기본으로 설정
            if (!empty($this->pages)) {
                $firstPage = $this->pages[0];
                $this->currentPage = [
                    'id' => $firstPage['id'],
                    'title' => $firstPage['title'],
                    'description' => $firstPage['content'] ?? $firstPage['title'],
                    'breadcrumb' => $firstPage['title']
                ];
            }
        }
    }

    public function loadPages()
    {
        $this->isLoading = true;

        try {
            // ProjectPage 모델 사용하여 계층 구조 로드
            $topLevelPages = ProjectPage::where('project_id', $this->projectId)
                ->whereNull('parent_id')
                ->with(['children' => function($query) {
                    $query->orderBy('sort_order');
                }])
                ->orderBy('sort_order')
                ->get();
            
            $this->pages = $this->buildHierarchy($topLevelPages->toArray());
            
        } catch (\Exception $e) {
            $this->pages = [];
        } finally {
            $this->isLoading = false;
        }
    }

    private function buildHierarchy($pages)
    {
        $result = [];
        foreach ($pages as $page) {
            $pageArray = $page;
            if (isset($page['children']) && count($page['children']) > 0) {
                $pageArray['children'] = $this->buildHierarchy($page['children']);
            }
            $result[] = $pageArray;
        }
        return $result;
    }

    #[On('pageCreated')]
    public function onPageCreated()
    {
        $this->loadPages();
    }

    public function switchPage($pageData)
    {
        $this->currentPage = [
            'id' => $pageData['id'],
            'title' => $pageData['title'],
            'description' => $pageData['content'] ?? $pageData['title'],
            'breadcrumb' => $pageData['title']
        ];
        
        $this->dispatch('pageChanged', $this->currentPage);
    }

    /**
     * 하위 페이지 추가
     */
    public function addChildPage($parentId)
    {
        $parentPage = ProjectPage::findOrFail($parentId);
        
        // 하위 페이지 개수를 기반으로 순서 결정
        $childrenCount = ProjectPage::where('parent_id', $parentId)->count();
        
        $childPage = ProjectPage::create([
            'title' => '새 하위 페이지 ' . ($childrenCount + 1),
            'slug' => 'child-page-' . uniqid(),
            'content' => '',
            'status' => 'draft',
            'project_id' => $this->projectId,
            'parent_id' => $parentId,
            'user_id' => auth()->id() ?: 1, // 인증되지 않은 경우 기본 사용자 ID
            'sort_order' => $childrenCount
        ]);
        
        $this->loadPages();
        $this->dispatch('pageCreated', $childPage->id);
    }

    public function render()
    {
        return view('300-page-service.308-page-project-dashboard.301-page-list-livewire');
    }
}