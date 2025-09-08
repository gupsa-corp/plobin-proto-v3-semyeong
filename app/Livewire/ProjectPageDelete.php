<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProjectPage;
use App\Models\ProjectChangeLog;
use Illuminate\Support\Facades\Auth;

class ProjectPageDelete extends Component
{
    public $projectId;
    public $organizationId;
    public $selectedPages = [];
    public $confirmText = '';
    public $showDeleteModal = false;
    public $deleteConfirmation = '';

    protected $rules = [
        'selectedPages' => 'required|array|min:1',
        'deleteConfirmation' => 'required|in:삭제'
    ];

    protected $messages = [
        'selectedPages.required' => '삭제할 페이지를 선택해주세요.',
        'selectedPages.min' => '최소 1개 이상의 페이지를 선택해주세요.',
        'deleteConfirmation.required' => '삭제 확인을 위해 "삭제"를 입력해주세요.',
        'deleteConfirmation.in' => '"삭제"를 정확히 입력해주세요.'
    ];

    public function mount($projectId, $organizationId)
    {
        $this->projectId = $projectId;
        $this->organizationId = $organizationId;
    }

    public function showDeleteConfirmation()
    {
        $this->validate(['selectedPages' => 'required|array|min:1']);
        $this->showDeleteModal = true;
        $this->deleteConfirmation = '';
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteConfirmation = '';
    }

    public function deletePages()
    {
        $this->validate();

        try {
            $pages = ProjectPage::whereIn('id', $this->selectedPages)
                ->where('project_id', $this->projectId)
                ->get();

            foreach ($pages as $page) {
                // 로그 기록
                ProjectChangeLog::logChange(
                    $this->projectId,
                    'page_deleted',
                    "페이지 '{$page->title}'가 삭제되었습니다.",
                    [
                        'page_id' => $page->id,
                        'page_title' => $page->title,
                        'deleted_by' => Auth::id()
                    ]
                );

                $page->forceDelete(); // 완전 삭제 (soft delete가 아닌)
            }

            session()->flash('message', count($this->selectedPages) . '개의 페이지가 삭제되었습니다.');
            
            $this->selectedPages = [];
            $this->showDeleteModal = false;
            $this->deleteConfirmation = '';

        } catch (\Exception $e) {
            session()->flash('error', '페이지 삭제 중 오류가 발생했습니다.');
        }
    }

    public function selectAll()
    {
        $pages = ProjectPage::where('project_id', $this->projectId)
            ->whereNull('deleted_at')
            ->pluck('id')->toArray();
        $this->selectedPages = $pages;
    }

    public function deselectAll()
    {
        $this->selectedPages = [];
    }


    public function render()
    {
        // soft delete되지 않은 페이지들만 조회
        $pages = ProjectPage::where('project_id', $this->projectId)
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get();

        return view('livewire.700-livewire-project-page-delete', compact('pages'));
    }
}