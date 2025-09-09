<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProjectPage;
use Illuminate\Support\Facades\Auth;

class PageList extends Component
{
    public $pages = [];
    public $currentPage = null;
    public $orgId;
    public $projectId;
    public $isLoading = false;
    public $editingPageId = null;
    public $editingTitle = '';

    public function mount($pages = [], $currentPage = null, $orgId = null, $projectId = null)
    {
        $this->pages = $pages;
        $this->currentPage = $currentPage;
        $this->orgId = $orgId;
        $this->projectId = $projectId;
    }

    public function updatePageOrder($pageId, $newIndex, $beforePageId = null, $afterPageId = null)
    {
        try {
            // 페이지 순서 업데이트 로직 (기존과 동일)
            $page = ProjectPage::find($pageId);
            if (!$page) {
                return;
            }

            // 순서 업데이트 처리
            $page->order_index = $newIndex;
            $page->save();

            // 성공 후 전체 컴포넌트 재렌더링
            $this->dispatch('pageOrderUpdated');
            
        } catch (\Exception $e) {
            // 에러 처리
            session()->flash('error', '페이지 순서 변경에 실패했습니다.');
        }
    }

    public function updatePageTitle($pageId, $title)
    {
        try {
            $page = ProjectPage::find($pageId);
            if ($page) {
                $page->title = $title;
                $page->save();
                
                // 성공 후 재렌더링
                $this->dispatch('pageTitleUpdated');
            }
        } catch (\Exception $e) {
            session()->flash('error', '페이지 이름 변경에 실패했습니다.');
        }
    }

    public function addChildPage($parentId)
    {
        try {
            $childPage = new ProjectPage();
            $childPage->title = '새 하위 페이지';
            $childPage->parent_id = $parentId;
            $childPage->project_id = $this->projectId;
            $childPage->user_id = Auth::id() ?? 1; // 인증되지 않은 경우 기본 사용자 ID 1
            $childPage->order_index = 0;
            $childPage->save();

            // 성공 후 재렌더링
            $this->dispatch('childPageAdded');
            
        } catch (\Exception $e) {
            session()->flash('error', '하위 페이지 생성에 실패했습니다.');
        }
    }

    public function deletePage($pageId)
    {
        try {
            $page = ProjectPage::find($pageId);
            if ($page) {
                $page->delete();
                
                // 성공 후 재렌더링
                $this->dispatch('pageDeleted');
            }
        } catch (\Exception $e) {
            session()->flash('error', '페이지 삭제에 실패했습니다.');
        }
    }

    public function render()
    {
        return view('livewire.page-list');
    }
}