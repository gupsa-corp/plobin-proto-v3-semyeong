<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectPage;
use App\Models\Organization;

class OrganizationDashboard extends Component
{
    public $organizationId;
    public $projects = [];
    public $pages = [];
    public $totalProjects = 0;
    public $totalPages = 0;
    public $selectedProject = null;
    public $organization = null;

    public function mount($organizationId)
    {
        $this->organizationId = $organizationId;
        $this->organization = Organization::find($organizationId);
        $this->loadData();
    }

    public function loadData()
    {
        // 해당 조직의 프로젝트들 조회
        $this->projects = Project::with(['organization', 'user'])
            ->where('organization_id', $this->organizationId)
            ->latest()
            ->take(10)
            ->get()
            ->toArray();

        // 해당 조직의 페이지들 조회 (프로젝트를 통해)
        $projectIds = collect($this->projects)->pluck('id')->toArray();
        
        if (!empty($projectIds)) {
            $this->pages = ProjectPage::with(['project', 'user'])
                ->whereIn('project_id', $projectIds)
                ->latest()
                ->take(20)
                ->get()
                ->toArray();
        } else {
            $this->pages = [];
        }

        // 전체 개수 (해당 조직 내)
        $this->totalProjects = Project::where('organization_id', $this->organizationId)->count();
        $this->totalPages = ProjectPage::whereIn('project_id', 
            Project::where('organization_id', $this->organizationId)->pluck('id')
        )->count();
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
        return view('organization-dashboard');
    }
}
