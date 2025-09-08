<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectPage;

class SandboxDashboard extends Component
{
    public $projects = [];
    public $pages = [];
    public $totalProjects = 0;
    public $totalPages = 0;
    public $selectedProject = null;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // 최근 프로젝트 5개 조회
        $this->projects = Project::with(['organization', 'user'])
            ->latest()
            ->take(5)
            ->get()
            ->toArray();

        // 최근 페이지 10개 조회
        $this->pages = ProjectPage::with(['project', 'user'])
            ->latest()
            ->take(10)
            ->get()
            ->toArray();

        // 전체 개수
        $this->totalProjects = Project::count();
        $this->totalPages = ProjectPage::count();
    }

    public function selectProject($projectId)
    {
        $this->selectedProject = $projectId;
        
        // 선택된 프로젝트의 페이지들 로드
        $this->pages = ProjectPage::with(['project', 'user'])
            ->where('project_id', $projectId)
            ->latest()
            ->get()
            ->toArray();
    }

    public function showAllProjects()
    {
        $this->selectedProject = null;
        $this->loadData();
    }

    public function render()
    {
        return view('sandbox-dashboard');
    }
}
