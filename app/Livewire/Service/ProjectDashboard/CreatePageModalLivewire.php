<?php

namespace App\Livewire\Service\ProjectDashboard;

use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectPage;
use Livewire\Component;

class CreatePageModalLivewire extends Component
{
    public $projectId;
    public $showCreateModal = false;
    public $title = '';
    public $content = '';
    public $status = 'draft';
    public $parent_id = null;
    public $isLoading = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'nullable|string',
        'status' => 'in:draft,published,archived',
        'parent_id' => 'nullable|exists:project_pages,id',
    ];

    protected $messages = [
        'title.required' => '제목을 입력해주세요.',
        'title.max' => '제목은 최대 255자까지 입력 가능합니다.',
    ];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
    }

    public function toggleCreateModal()
    {
        $this->showCreateModal = !$this->showCreateModal;
        if (!$this->showCreateModal) {
            $this->resetCreateForm();
        }
    }

    public function createPage()
    {
        $this->validate();

        $this->isLoading = true;

        try {
            $project = Project::findOrFail($this->projectId);
            
            // slug 생성 (제목을 기반으로)
            $slug = \Illuminate\Support\Str::slug($this->title);
            
            // 같은 프로젝트에서 동일한 slug가 있는지 확인하여 유니크하게 만들기
            $originalSlug = $slug;
            $counter = 1;
            while (ProjectPage::where('project_id', $this->projectId)->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            // sort_order 결정 (같은 레벨의 페이지 개수 + 1)
            $sortOrder = ProjectPage::where('project_id', $this->projectId)
                ->where('parent_id', $this->parent_id)
                ->count();
            
            ProjectPage::create([
                'title' => $this->title,
                'slug' => $slug,
                'content' => $this->content,
                'status' => $this->status,
                'project_id' => $this->projectId,
                'parent_id' => $this->parent_id,
                'user_id' => auth()->id(),
                'sort_order' => $sortOrder,
            ]);

            $this->resetCreateForm();
            $this->showCreateModal = false;
            
            session()->flash('message', '페이지가 생성되었습니다.');
            $this->dispatch('pageCreated');
            
        } catch (\Exception $e) {
            session()->flash('error', '페이지 생성에 실패했습니다.');
        } finally {
            $this->isLoading = false;
        }
    }

    public function resetCreateForm()
    {
        $this->title = '';
        $this->content = '';
        $this->status = 'draft';
        $this->parent_id = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('300-page-service.308-page-project-dashboard.300-create-page-modal-livewire');
    }
}