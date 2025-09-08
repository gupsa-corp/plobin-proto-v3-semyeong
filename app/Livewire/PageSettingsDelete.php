<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProjectPage;
use App\Services\ProjectLogService;

class PageSettingsDelete extends Component
{
    public $pageId;
    public $confirmText = '';

    protected $rules = [
        'confirmText' => 'required|in:삭제',
    ];

    protected $messages = [
        'confirmText.required' => '삭제를 확인하려면 "삭제"를 입력해주세요.',
        'confirmText.in' => '삭제를 확인하려면 "삭제"를 정확히 입력해주세요.',
    ];

    public function mount($pageId)
    {
        $this->pageId = $pageId;
    }

    public function deletePage()
    {
        $this->validate();

        $page = ProjectPage::with('project')->findOrFail($this->pageId);
        
        // 하위 페이지가 있는지 확인
        $hasChildPages = ProjectPage::where('parent_id', $this->pageId)->exists();
        if ($hasChildPages) {
            session()->flash('error', '하위 페이지가 있는 페이지는 삭제할 수 없습니다. 먼저 하위 페이지를 삭제해주세요.');
            return;
        }

        $projectId = $page->project_id;
        $organizationId = $page->project->organization_id;
        $pageTitle = $page->title;

        // 로그 기록
        ProjectLogService::logPageDeleted($projectId, $this->pageId, $pageTitle);

        // 페이지 삭제
        $page->delete();

        session()->flash('success', '페이지가 성공적으로 삭제되었습니다.');
        
        // 프로젝트 대시보드로 리다이렉트
        return redirect()->route('project.dashboard', [
            'id' => $organizationId,
            'projectId' => $projectId
        ]);
    }

    public function render()
    {
        $page = ProjectPage::with('project')->findOrFail($this->pageId);
        return view('700-livewire-page-settings-delete', compact('page'));
    }
}