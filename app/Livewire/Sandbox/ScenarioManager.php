<?php

namespace App\Livewire\Sandbox;

use Livewire\Component;
use App\Models\SandboxScenarioGroup;
use App\Models\SandboxScenario;
use App\Models\SandboxSubScenario;
use App\Models\SandboxScenarioStep;
use App\Models\SandboxScenarioComment;
use Illuminate\Support\Facades\Auth;

class ScenarioManager extends Component
{
    // UI 상태 관리
    public $activeTab = 'list';
    public $selectedScenarioId = null;
    public $selectedScenario = null;
    public $selectedSubScenario = null;
    public $selectedStep = null;

    // 브레드크럼 네비게이션
    public $breadcrumb = [];

    // 검색 및 필터링
    public $searchTerm = '';
    public $statusFilter = 'all';
    public $priorityFilter = 'all';
    public $groupFilter = 'all';

    // 폼 데이터
    public $newScenario = [
        'title' => '',
        'description' => '',
        'priority' => 'medium',
        'group_id' => null,
    ];

    public $editingScenario = null;

    // 메시지
    public $message = '';
    public $messageType = 'success';

    // 리스너
    protected $listeners = [
        'scenarioCreated' => 'handleScenarioCreated',
        'scenarioUpdated' => 'handleScenarioUpdated',
        'scenarioDeleted' => 'handleScenarioDeleted',
    ];

    public function mount()
    {
        // 기본 그룹이 없으면 생성
        if (SandboxScenarioGroup::count() === 0) {
            $this->createDefaultGroups();
        }
    }

    /**
     * 기본 시나리오 그룹들 생성
     */
    private function createDefaultGroups()
    {
        $defaultGroups = [
            ['name' => '개발 프로젝트', 'description' => '소프트웨어 개발 관련 시나리오들', 'color' => '#3B82F6', 'icon' => 'code'],
            ['name' => '비즈니스 프로세스', 'description' => '비즈니스 운영 관련 시나리오들', 'color' => '#10B981', 'icon' => 'briefcase'],
            ['name' => '개인 프로젝트', 'description' => '개인적인 프로젝트 및 아이디어들', 'color' => '#F59E0B', 'icon' => 'user'],
        ];

        foreach ($defaultGroups as $group) {
            SandboxScenarioGroup::create(array_merge($group, [
                'created_by' => Auth::id(),
            ]));
        }
    }

    /**
     * 필터링된 시나리오들 조회
     */
    public function getFilteredScenarios()
    {
        $query = SandboxScenario::with(['group', 'assignee', 'subScenarios.steps']);

        // 검색어 필터링
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // 상태 필터링
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // 우선순위 필터링
        if ($this->priorityFilter !== 'all') {
            $query->where('priority', $this->priorityFilter);
        }

        // 그룹 필터링
        if ($this->groupFilter !== 'all') {
            $query->where('group_id', $this->groupFilter);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * 시나리오 그룹들 조회
     */
    public function getGroups()
    {
        return SandboxScenarioGroup::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
    }

    /**
     * 새 시나리오 생성
     */
    public function createScenario()
    {
        $this->validate([
            'newScenario.title' => 'required|string|max:255',
            'newScenario.description' => 'nullable|string',
            'newScenario.priority' => 'required|in:low,medium,high,critical',
            'newScenario.group_id' => 'required|exists:scenario_groups,id',
        ]);

        try {
            $scenario = SandboxScenario::create([
                'group_id' => $this->newScenario['group_id'],
                'title' => $this->newScenario['title'],
                'description' => $this->newScenario['description'],
                'priority' => $this->newScenario['priority'],
                'created_by' => Auth::id(),
            ]);

            $this->showMessage('시나리오가 성공적으로 생성되었습니다.', 'success');
            $this->resetNewScenarioForm();
            $this->activeTab = 'list';

        } catch (\Exception $e) {
            $this->showMessage('시나리오 생성 중 오류가 발생했습니다.', 'error');
        }
    }

    /**
     * 시나리오 선택
     */
    public function selectScenario($scenarioId)
    {
        $this->selectedScenarioId = $scenarioId;
        $this->selectedScenario = SandboxScenario::with(['group', 'assignee', 'reporter', 'subScenarios.steps'])
            ->findOrFail($scenarioId);
        $this->selectedSubScenario = null;
        $this->selectedStep = null;
        $this->activeTab = 'detail';
        $this->breadcrumb = [
            ['type' => 'scenario', 'id' => $scenarioId, 'title' => $this->selectedScenario->title]
        ];
    }

    public function selectSubScenario($subScenarioId)
    {
        $this->selectedSubScenario = SandboxSubScenario::with(['scenario.group', 'assignee', 'steps', 'comments'])
            ->findOrFail($subScenarioId);
        $this->selectedStep = null;
        $this->breadcrumb = [
            ['type' => 'scenario', 'id' => $this->selectedSubScenario->scenario->id, 'title' => $this->selectedSubScenario->scenario->title],
            ['type' => 'sub-scenario', 'id' => $subScenarioId, 'title' => $this->selectedSubScenario->title]
        ];
    }

    public function selectStep($stepId)
    {
        $this->selectedStep = SandboxScenarioStep::with(['subScenario.scenario.group', 'assignee', 'comments'])
            ->findOrFail($stepId);
        $this->breadcrumb = [
            ['type' => 'scenario', 'id' => $this->selectedStep->subScenario->scenario->id, 'title' => $this->selectedStep->subScenario->scenario->title],
            ['type' => 'sub-scenario', 'id' => $this->selectedStep->subScenario->id, 'title' => $this->selectedStep->subScenario->title],
            ['type' => 'step', 'id' => $stepId, 'title' => $this->selectedStep->title]
        ];
    }

    public function navigateToBreadcrumb($index)
    {
        // 선택한 브레드크럼 위치까지 네비게이션
        $this->breadcrumb = array_slice($this->breadcrumb, 0, $index + 1);

        $lastCrumb = end($this->breadcrumb);

        if ($lastCrumb['type'] === 'scenario') {
            $this->selectScenario($lastCrumb['id']);
        } elseif ($lastCrumb['type'] === 'sub-scenario') {
            $this->selectSubScenario($lastCrumb['id']);
        } elseif ($lastCrumb['type'] === 'step') {
            $this->selectStep($lastCrumb['id']);
        }
    }

    public function goBackToList()
    {
        $this->activeTab = 'list';
        $this->selectedScenarioId = null;
        $this->selectedScenario = null;
        $this->selectedSubScenario = null;
        $this->selectedStep = null;
        $this->breadcrumb = [];
    }

    /**
     * 시나리오 편집 모드 시작
     */
    public function startEditingScenario($scenarioId)
    {
        $scenario = SandboxScenario::findOrFail($scenarioId);
        $this->editingScenario = [
            'id' => $scenario->id,
            'title' => $scenario->title,
            'description' => $scenario->description,
            'priority' => $scenario->priority,
            'status' => $scenario->status,
            'group_id' => $scenario->group_id,
        ];
    }

    /**
     * 시나리오 편집 저장
     */
    public function saveScenarioEdit()
    {
        $this->validate([
            'editingScenario.title' => 'required|string|max:255',
            'editingScenario.description' => 'nullable|string',
            'editingScenario.priority' => 'required|in:low,medium,high,critical',
            'editingScenario.status' => 'required|in:backlog,todo,in-progress,review,done,cancelled',
            'editingScenario.group_id' => 'required|exists:scenario_groups,id',
        ]);

        try {
            $scenario = Scenario::findOrFail($this->editingScenario['id']);
            $scenario->update([
                'title' => $this->editingScenario['title'],
                'description' => $this->editingScenario['description'],
                'priority' => $this->editingScenario['priority'],
                'status' => $this->editingScenario['status'],
                'group_id' => $this->editingScenario['group_id'],
            ]);

            $this->showMessage('시나리오가 성공적으로 업데이트되었습니다.', 'success');
            $this->editingScenario = null;

            // 선택된 시나리오가 수정된 경우 다시 로드
            if ($this->selectedScenarioId === $scenario->id) {
                $this->selectScenario($scenario->id);
            }

        } catch (\Exception $e) {
            $this->showMessage('시나리오 업데이트 중 오류가 발생했습니다.', 'error');
        }
    }

    /**
     * 시나리오 편집 취소
     */
    public function cancelScenarioEdit()
    {
        $this->editingScenario = null;
    }

    /**
     * 시나리오 삭제
     */
    public function deleteScenario($scenarioId)
    {
        try {
            $scenario = SandboxScenario::findOrFail($scenarioId);
            $scenario->delete();

            $this->showMessage('시나리오가 성공적으로 삭제되었습니다.', 'success');

            // 삭제된 시나리오가 선택된 경우 목록으로 돌아감
            if ($this->selectedScenarioId === $scenarioId) {
                $this->selectedScenarioId = null;
                $this->selectedScenario = null;
                $this->activeTab = 'list';
            }

        } catch (\Exception $e) {
            $this->showMessage('시나리오 삭제 중 오류가 발생했습니다.', 'error');
        }
    }

    /**
     * 새 시나리오 폼 초기화
     */
    private function resetNewScenarioForm()
    {
        $this->newScenario = [
            'title' => '',
            'description' => '',
            'priority' => 'medium',
            'group_id' => null,
        ];
    }

    /**
     * 메시지 표시
     */
    private function showMessage($message, $type = 'success')
    {
        $this->message = $message;
        $this->messageType = $type;

        // 3초 후 메시지 자동 제거
        $this->dispatch('message-timeout');
    }

    /**
     * 이벤트 핸들러들
     */
    public function handleScenarioCreated()
    {
        $this->showMessage('시나리오가 생성되었습니다.', 'success');
    }

    public function handleScenarioUpdated()
    {
        $this->showMessage('시나리오가 업데이트되었습니다.', 'success');
    }

    public function handleScenarioDeleted()
    {
        $this->showMessage('시나리오가 삭제되었습니다.', 'success');
    }

    public function render()
    {
        return view('livewire.sandbox.scenario-manager', [
            'scenarios' => $this->getFilteredScenarios(),
            'groups' => $this->getGroups(),
        ]);
    }
}
