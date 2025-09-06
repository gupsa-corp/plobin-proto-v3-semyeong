<?php

namespace App\Livewire\Service\ProjectDashboard;

use App\Models\Page;
use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\On;

class PageListLivewire extends Component
{
    public $projectId;
    public $pages = [];
    public $currentPage = null;
    public $isLoading = false;

    protected $listeners = ['pageCreated' => 'loadPages'];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->loadPages();
    }

    public function loadPages()
    {
        $this->isLoading = true;

        try {
            $project = Project::with('pages')->findOrFail($this->projectId);
            $this->pages = $project->pages->toArray();
            
            if (count($this->pages) > 0) {
                $this->currentPage = $this->pages[0];
            } else {
                $this->currentPage = [
                    'id' => 'dashboard-home',
                    'title' => '프로젝트 대시보드',
                    'description' => '프로젝트 진행 상황과 주요 메트릭을 확인하세요',
                    'breadcrumb' => '대시보드 홈'
                ];
            }
        } catch (\Exception $e) {
            $this->pages = [];
            $this->currentPage = [
                'id' => 'dashboard-home',
                'title' => '프로젝트 대시보드',
                'description' => '프로젝트 진행 상황과 주요 메트릭을 확인하세요',
                'breadcrumb' => '대시보드 홈'
            ];
        } finally {
            $this->isLoading = false;
        }
    }

    #[On('pageCreated')]
    public function onPageCreated()
    {
        $this->loadPages();
    }

    public function switchPage($pageData)
    {
        if (is_string($pageData)) {
            if ($pageData === 'dashboard-home') {
                $this->currentPage = [
                    'id' => 'dashboard-home',
                    'title' => '프로젝트 대시보드',
                    'description' => '프로젝트 진행 상황과 주요 메트릭을 확인하세요',
                    'breadcrumb' => '대시보드 홈'
                ];
            }
        } else {
            $this->currentPage = [
                'id' => $pageData['id'],
                'title' => $pageData['title'],
                'description' => $pageData['content'] ?? $pageData['title'],
                'breadcrumb' => '대시보드 홈 > ' . $pageData['title']
            ];
        }
        
        $this->dispatch('pageChanged', $this->currentPage);
    }

    public function render()
    {
        return view('300-page-service.308-page-project-dashboard.301-page-list-livewire');
    }
}