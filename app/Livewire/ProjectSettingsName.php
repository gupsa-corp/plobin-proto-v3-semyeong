<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;

class ProjectSettingsName extends Component
{
    public $projectId;
    public $organizationId;
    public $projectName = '';
    public $originalName = '';
    
    public function mount($projectId, $organizationId)
    {
        $this->projectId = $projectId;
        $this->organizationId = $organizationId;
        
        $project = Project::find($this->projectId);
        if ($project) {
            $this->projectName = $project->name;
            $this->originalName = $project->name;
        }
    }
    
    public function updateName()
    {
        $this->validate([
            'projectName' => 'required|string|max:255|min:1',
        ], [
            'projectName.required' => '프로젝트 이름은 필수입니다.',
            'projectName.max' => '프로젝트 이름은 255자를 초과할 수 없습니다.',
            'projectName.min' => '프로젝트 이름은 최소 1자 이상이어야 합니다.',
        ]);
        
        $project = Project::find($this->projectId);
        if ($project) {
            $project->update([
                'name' => trim($this->projectName)
            ]);
            
            $this->originalName = $this->projectName;
            
            session()->flash('message', '프로젝트 이름이 성공적으로 변경되었습니다.');
            
            // 페이지 새로고침을 통해 사이드바의 프로젝트 이름도 업데이트
            return redirect()->route('project.dashboard.project.settings.name', [
                'id' => $this->organizationId,
                'projectId' => $this->projectId
            ]);
        }
    }
    
    public function cancel()
    {
        $this->projectName = $this->originalName;
    }
    
    public function render()
    {
        return view('livewire.700-livewire-project-settings-name');
    }
}
