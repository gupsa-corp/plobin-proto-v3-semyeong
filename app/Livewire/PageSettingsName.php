<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProjectPage;
use App\Services\ProjectChangeLogService;

class PageSettingsName extends Component
{
    public $pageId;
    public $title;
    public $originalTitle;

    protected $rules = [
        'title' => 'required|min:1|max:255',
    ];

    protected $messages = [
        'title.required' => '페이지 제목을 입력해주세요.',
        'title.min' => '페이지 제목은 최소 1글자 이상이어야 합니다.',
        'title.max' => '페이지 제목은 255글자를 초과할 수 없습니다.',
    ];

    public function mount($pageId)
    {
        $this->pageId = $pageId;
        $page = ProjectPage::findOrFail($pageId);
        $this->title = $page->title;
        $this->originalTitle = $page->title;
    }

    public function updateTitle()
    {
        $this->validate();

        $page = ProjectPage::findOrFail($this->pageId);
        
        // 제목이 변경된 경우에만 업데이트
        if ($this->title !== $this->originalTitle) {
            $oldTitle = $page->title;
            $page->update(['title' => $this->title]);
            
            // 로그 기록
            ProjectChangeLogService::logPageUpdated(
                $page->project_id,
                $page->id,
                $this->title,
                ['title' => [$oldTitle, $this->title]]
            );

            $this->originalTitle = $this->title;
            
            session()->flash('success', '페이지 제목이 성공적으로 변경되었습니다.');
            
            // 사이드바 갱신을 위한 이벤트 발생
            $this->dispatch('page-title-updated', [
                'pageId' => $this->pageId,
                'newTitle' => $this->title
            ]);
        }
    }

    public function render()
    {
        $page = ProjectPage::with('project')->findOrFail($this->pageId);
        return view('700-livewire-page-settings-name', compact('page'));
    }
}
