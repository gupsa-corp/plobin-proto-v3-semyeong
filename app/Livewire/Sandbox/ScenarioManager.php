<?php

namespace App\Livewire\Sandbox;

use Livewire\Component;
use App\Models\SandboxScenario;
use App\Models\SandboxScenarioRequirement;

class ScenarioManager extends Component
{
    public string $activeTab = 'list';
    public ?int $selectedScenarioId = null;
    
    // 폼 데이터
    public string $title = '';
    public string $description = '';
    public string $priority = 'medium';
    public string $status = 'todo';
    
    // 요구사항 폼
    public string $requirementContent = '';
    public ?int $parentRequirementId = null;
    
    // 필터링
    public string $searchTerm = '';
    public string $statusFilter = 'all';
    public string $priorityFilter = 'all';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority' => 'required|in:low,medium,high',
        'status' => 'required|in:todo,in-progress,done,cancelled',
        'requirementContent' => 'required|string|max:1000',
    ];

    public function mount()
    {
        // 초기화 로직
    }

    // 탭 전환
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        
        if ($tab === 'create') {
            $this->resetForm();
        }
    }

    // 시나리오 선택
    public function selectScenario($id)
    {
        $this->selectedScenarioId = $id;
        $this->activeTab = 'detail';
        
        // 선택된 시나리오 데이터 로드
        $scenario = SandboxScenario::find($id);
        if ($scenario) {
            $this->title = $scenario->title;
            $this->description = $scenario->description;
            $this->priority = $scenario->priority;
            $this->status = $scenario->status;
        }
    }

    // 시나리오 생성
    public function createScenario()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $maxSortOrder = SandboxScenario::max('sort_order') ?? 0;

        SandboxScenario::create([
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'sort_order' => $maxSortOrder + 1,
        ]);

        $this->resetForm();
        $this->activeTab = 'list';
        
        session()->flash('message', '시나리오가 생성되었습니다.');
    }

    // 시나리오 업데이트
    public function updateScenario()
    {
        if (!$this->selectedScenarioId) return;

        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in-progress,done,cancelled',
        ]);

        $scenario = SandboxScenario::find($this->selectedScenarioId);
        if ($scenario) {
            $scenario->update([
                'title' => $this->title,
                'description' => $this->description,
                'priority' => $this->priority,
                'status' => $this->status,
            ]);
            
            session()->flash('message', '시나리오가 업데이트되었습니다.');
        }
    }

    // 시나리오 삭제
    public function deleteScenario($id)
    {
        $scenario = SandboxScenario::find($id);
        if ($scenario) {
            $scenario->delete();
            
            if ($this->selectedScenarioId === $id) {
                $this->selectedScenarioId = null;
                $this->activeTab = 'list';
            }
            
            session()->flash('message', '시나리오가 삭제되었습니다.');
        }
    }

    // 요구사항 추가
    public function addRequirement()
    {
        if (!$this->selectedScenarioId) return;

        $this->validate([
            'requirementContent' => 'required|string|max:1000',
        ]);

        $maxSortOrder = SandboxScenarioRequirement::where('sandbox_scenario_id', $this->selectedScenarioId)
            ->where('parent_id', $this->parentRequirementId)
            ->max('sort_order') ?? 0;

        SandboxScenarioRequirement::create([
            'sandbox_scenario_id' => $this->selectedScenarioId,
            'parent_id' => $this->parentRequirementId,
            'content' => $this->requirementContent,
            'sort_order' => $maxSortOrder + 1,
        ]);

        $this->requirementContent = '';
        $this->parentRequirementId = null;
        
        session()->flash('message', '요구사항이 추가되었습니다.');
    }

    // 요구사항 완료 상태 토글
    public function toggleRequirement($requirementId)
    {
        $requirement = SandboxScenarioRequirement::find($requirementId);
        if ($requirement) {
            $requirement->update([
                'completed' => !$requirement->completed
            ]);
        }
    }

    // 요구사항 삭제
    public function deleteRequirement($requirementId)
    {
        $requirement = SandboxScenarioRequirement::find($requirementId);
        if ($requirement) {
            $requirement->delete();
            session()->flash('message', '요구사항이 삭제되었습니다.');
        }
    }

    // 폼 리셋
    public function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->priority = 'medium';
        $this->status = 'todo';
        $this->requirementContent = '';
        $this->parentRequirementId = null;
    }

    // 시나리오 상태 변경
    public function updateStatus($scenarioId, $status)
    {
        $scenario = SandboxScenario::find($scenarioId);
        if ($scenario) {
            $scenario->update(['status' => $status]);
        }
    }

    // 우선순위 변경
    public function updatePriority($scenarioId, $priority)
    {
        $scenario = SandboxScenario::find($scenarioId);
        if ($scenario) {
            $scenario->update(['priority' => $priority]);
        }
    }

    public function render()
    {
        $scenarios = SandboxScenario::with(['requirements.children'])
            ->when($this->searchTerm, function($query) {
                $query->where('title', 'like', "%{$this->searchTerm}%")
                      ->orWhere('description', 'like', "%{$this->searchTerm}%");
            })
            ->when($this->statusFilter !== 'all', function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter !== 'all', function($query) {
                $query->where('priority', $this->priorityFilter);
            })
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedScenario = null;
        if ($this->selectedScenarioId) {
            $selectedScenario = SandboxScenario::with(['requirements.children'])
                ->find($this->selectedScenarioId);
        }

        return view('livewire.sandbox.scenario-manager', [
            'scenarios' => $scenarios,
            'selectedScenario' => $selectedScenario,
        ]);
    }
}