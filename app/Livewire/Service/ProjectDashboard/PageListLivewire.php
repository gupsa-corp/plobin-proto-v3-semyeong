<?php

namespace App\Livewire\Service\ProjectDashboard;

use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectPage;
use App\Services\ProjectLogService;
use Livewire\Component;
use Livewire\Attributes\On;

class PageListLivewire extends Component
{
    public $orgId;
    public $projectId;
    public $pages = [];
    public $currentPage = null;
    public $isLoading = false;
    public $editingPageId = null;
    public $editingTitle = '';

    protected $listeners = [
        'pageCreated' => 'loadPages',
        'add-parent-page' => 'addParentPage',
        'page-title-updated' => 'onPageTitleUpdated'
    ];

    public function mount($orgId, $projectId, $currentPageId = null)
    {
        $this->orgId = $orgId;
        $this->projectId = $projectId;
        $this->loadPages();
        $this->setCurrentPageFromUrl($currentPageId);
    }
    
    private function setCurrentPageFromUrl($currentPageId = null)
    {
        if ($currentPageId) {
            // 재귀적으로 페이지 찾기 (하위 페이지 포함)
            $page = $this->findPageRecursively($this->pages, $currentPageId);
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

    /**
     * 재귀적으로 페이지 찾기 (하위 페이지 포함)
     */
    private function findPageRecursively($pages, $pageId)
    {
        foreach ($pages as $page) {
            // 현재 페이지가 찾는 페이지인지 확인
            if ($page['id'] == $pageId) {
                return $page;
            }
            
            // 하위 페이지가 있으면 재귀적으로 검색
            if (isset($page['children']) && count($page['children']) > 0) {
                $found = $this->findPageRecursively($page['children'], $pageId);
                if ($found) {
                    return $found;
                }
            }
        }
        
        return null;
    }

    public function loadPages()
    {
        $this->isLoading = true;

        try {
            // 모든 페이지를 가져와서 계층 구조 직접 구성
            $allPages = ProjectPage::where('project_id', $this->projectId)
                ->orderBy('sort_order')
                ->get()
                ->toArray();
            
            $this->pages = $this->buildCompleteHierarchy($allPages);
            
        } catch (\Exception $e) {
            $this->pages = [];
        } finally {
            $this->isLoading = false;
        }
    }

    private function buildCompleteHierarchy($allPages, $parentId = null)
    {
        $result = [];
        
        foreach ($allPages as $page) {
            if ($page['parent_id'] == $parentId) {
                $pageArray = $page;
                $children = $this->buildCompleteHierarchy($allPages, $page['id']);
                if (count($children) > 0) {
                    $pageArray['children'] = $children;
                }
                $result[] = $pageArray;
            }
        }
        
        return $result;
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
     * 부모 페이지 추가 (최상위 페이지)
     */
    public function addParentPage()
    {
        // 최상위 페이지 개수를 기반으로 순서 결정
        $parentCount = ProjectPage::where('project_id', $this->projectId)
            ->whereNull('parent_id')
            ->count();
        
        $parentPage = ProjectPage::create([
            'title' => '새 페이지 ' . ($parentCount + 1),
            'slug' => 'parent-page-' . uniqid(),
            'content' => '',
            'status' => 'draft',
            'project_id' => $this->projectId,
            'parent_id' => null,
            'user_id' => auth()->id(),
            'sort_order' => $parentCount
        ]);
        
        $this->loadPages();
        $this->dispatch('pageCreated', $parentPage->id);
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
            'user_id' => auth()->id(), // 인증되지 않은 경우 기본 사용자 ID
            'sort_order' => $childrenCount
        ]);
        
        $this->loadPages();
        $this->dispatch('pageCreated', $childPage->id);
    }

    /**
     * 인라인 편집 시작
     */
    public function startEditing($pageId, $currentTitle)
    {
        $this->editingPageId = $pageId;
        $this->editingTitle = $currentTitle;
    }

    /**
     * 인라인 편집 취소
     */
    public function cancelEditing()
    {
        $this->editingPageId = null;
        $this->editingTitle = '';
    }

    /**
     * 페이지 제목 업데이트 (엔터키 또는 포커스 아웃)
     */
    public function updatePageTitle()
    {
        if ($this->editingPageId && trim($this->editingTitle) !== '') {
            $page = ProjectPage::find($this->editingPageId);
            
            if ($page && $page->title !== trim($this->editingTitle)) {
                $oldTitle = $page->title;
                $newTitle = trim($this->editingTitle);
                
                $page->update(['title' => $newTitle]);
                
                // 로그 기록
                ProjectLogService::logPageUpdated(
                    $page->project_id,
                    $page->id,
                    $newTitle,
                    ['title' => [$oldTitle, $newTitle]]
                );
                
                // 페이지 목록 새로고침
                $this->loadPages();
                
                // 현재 페이지가 편집된 페이지라면 정보 업데이트
                if ($this->currentPage && $this->currentPage['id'] == $this->editingPageId) {
                    $this->currentPage['title'] = $newTitle;
                    $this->currentPage['breadcrumb'] = $newTitle;
                }
                
                // 다른 컴포넌트에 업데이트 알림
                $this->dispatch('page-title-updated', [
                    'pageId' => $this->editingPageId,
                    'newTitle' => $newTitle
                ]);
            }
        }
        
        $this->cancelEditing();
    }

    /**
     * 페이지 제목 업데이트 이벤트 리스너
     */
    public function onPageTitleUpdated($data)
    {
        // 페이지 목록을 새로고침하여 변경사항 반영
        $this->loadPages();
    }

    /**
     * 페이지 제목 업데이트 (alert 프롬프트)
     */
    public function updatePageTitleWithPrompt($pageId)
    {
        $page = ProjectPage::find($pageId);
        if ($page) {
            $this->dispatch('show-rename-prompt', [
                'pageId' => $pageId,
                'currentTitle' => $page->title
            ]);
        }
    }

    /**
     * alert 프롬프트에서 받은 제목으로 업데이트
     */
    public function updatePageTitleFromPrompt($pageId, $newTitle)
    {
        if (!$newTitle || trim($newTitle) === '') {
            return;
        }

        $page = ProjectPage::find($pageId);
        if ($page && $page->title !== trim($newTitle)) {
            $oldTitle = $page->title;
            $newTitle = trim($newTitle);
            
            $page->update(['title' => $newTitle]);
            
            // 로그 기록
            ProjectLogService::logPageUpdated(
                $page->project_id,
                $page->id,
                $newTitle,
                ['title' => [$oldTitle, $newTitle]]
            );
            
            // 페이지 목록 새로고침
            $this->loadPages();
            
            // 현재 페이지가 편집된 페이지라면 정보 업데이트
            if ($this->currentPage && $this->currentPage['id'] == $pageId) {
                $this->currentPage['title'] = $newTitle;
                $this->currentPage['breadcrumb'] = $newTitle;
            }
            
            // 다른 컴포넌트에 업데이트 알림
            $this->dispatch('page-title-updated', [
                'pageId' => $pageId,
                'newTitle' => $newTitle
            ]);
        }
    }

    /**
     * 페이지 삭제
     */
    public function deletePage($pageId)
    {
        try {
            $page = ProjectPage::find($pageId);
            if (!$page) {
                return;
            }

            // 하위 페이지가 있는지 확인
            $hasChildren = ProjectPage::where('parent_id', $pageId)->exists();
            if ($hasChildren) {
                $this->dispatch('show-error', '하위 페이지가 있는 페이지는 삭제할 수 없습니다. 먼저 하위 페이지를 삭제하세요.');
                return;
            }

            // 현재 페이지가 삭제될 페이지인 경우 첫 번째 페이지로 리다이렉트
            $shouldRedirect = $this->currentPage && $this->currentPage['id'] == $pageId;

            // 페이지 삭제
            $page->delete();

            // 로그 기록
            ProjectLogService::logPageDeleted(
                $page->project_id,
                $pageId,
                $page->title
            );

            // 페이지 목록 새로고침
            $this->loadPages();

            if ($shouldRedirect) {
                // 첫 번째 페이지로 리다이렉트
                $firstPage = collect($this->pages)->first();
                if ($firstPage) {
                    $this->dispatch('redirect-to-page', $firstPage['id']);
                } else {
                    $this->dispatch('redirect-to-dashboard');
                }
            }

            $this->dispatch('show-success', '페이지가 삭제되었습니다.');
            
        } catch (\Exception $e) {
            $this->dispatch('show-error', '페이지 삭제 중 오류가 발생했습니다.');
        }
    }

    /**
     * 페이지 순서 변경 (SortableJS)
     */
    public function updatePageOrder($pageId, $newIndex, $beforePageId = null, $afterPageId = null)
    {
        try {
            $page = ProjectPage::find($pageId);
            if (!$page) {
                return;
            }

            // 같은 부모의 페이지들을 가져와서 순서 재정렬
            $siblings = ProjectPage::where('project_id', $this->projectId)
                ->whereNull('parent_id') // 현재는 최상위 페이지만 지원
                ->where('id', '!=', $pageId)
                ->orderBy('sort_order')
                ->get();

            // 새로운 순서로 재배치
            $newOrder = 0;
            foreach ($siblings as $sibling) {
                if ($newOrder == $newIndex) {
                    // 현재 이동한 페이지 위치
                    $page->update(['sort_order' => $newOrder]);
                    $newOrder++;
                }
                $sibling->update(['sort_order' => $newOrder]);
                $newOrder++;
            }

            // 마지막에 추가된 경우
            if ($newIndex >= count($siblings)) {
                $page->update(['sort_order' => $newOrder]);
            }

            // 페이지 목록 새로고침
            $this->loadPages();
            
        } catch (\Exception $e) {
            $this->dispatch('show-error', '페이지 순서 변경 중 오류가 발생했습니다.');
        }
    }

    /**
     * 하위 페이지인지 확인
     */
    private function isDescendant($ancestorId, $descendantId)
    {
        $page = ProjectPage::find($descendantId);
        
        while ($page && $page->parent_id) {
            if ($page->parent_id == $ancestorId) {
                return true;
            }
            $page = ProjectPage::find($page->parent_id);
        }
        
        return false;
    }

    /**
     * sort_order를 정수로 재정렬
     */
    private function reorderSortNumbers()
    {
        // 모든 부모별로 그룹화하여 정렬
        $allPages = ProjectPage::where('project_id', $this->projectId)
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get();

        $groups = $allPages->groupBy('parent_id');
        
        foreach ($groups as $parentId => $pages) {
            foreach ($pages as $index => $page) {
                $page->update(['sort_order' => $index]);
            }
        }
    }

    public function render()
    {
        return view('300-page-service.308-page-project-dashboard.301-page-list-livewire');
    }
}