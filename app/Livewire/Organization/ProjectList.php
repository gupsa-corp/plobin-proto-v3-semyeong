<?php

namespace App\Livewire\Organization;

use Livewire\Component;
use App\Models\Project;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class ProjectList extends Component
{
    public $projects = [];
    public $isLoading = true;
    public $organization;
    public $organizationId;

    protected $listeners = ['projectCreated' => 'loadProjects'];

    public function mount($organizationId)
    {
        $this->organizationId = $organizationId;
        $this->organization = Organization::find($organizationId);
        $this->loadProjects();
    }

    public function loadProjects()
    {
        // 해당 조직의 프로젝트들을 로드
        $this->projects = Project::where('organization_id', $this->organizationId)
            ->orderBy('created_at', 'desc')
            ->get();
        $this->isLoading = false;
    }

    public function render()
    {
        return view('300-page-service.307-page-organization-projects.300-livewire-project-list');
    }
}