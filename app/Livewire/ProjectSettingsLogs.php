<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProjectLog;
use App\Models\Project;

class ProjectSettingsLogs extends Component
{
    use WithPagination;

    public $projectId;
    public $filterAction = '';
    public $filterUser = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 20;

    protected $queryString = [
        'filterAction' => ['except' => ''],
        'filterUser' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
    }

    public function updatingFilterAction()
    {
        $this->resetPage();
    }

    public function updatingFilterUser()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['filterAction', 'filterUser', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render()
    {
        $query = ProjectLog::query()
            ->with(['user'])
            ->where('project_id', $this->projectId);

        // 액션 필터
        if ($this->filterAction) {
            $query->where('action', $this->filterAction);
        }

        // 사용자 필터
        if ($this->filterUser) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->filterUser . '%')
                  ->orWhere('email', 'like', '%' . $this->filterUser . '%');
            });
        }

        // 날짜 필터
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $logs = $query->orderBy('created_at', 'desc')
                     ->paginate($this->perPage);

        // 프로젝트 정보
        $project = Project::findOrFail($this->projectId);

        // 사용 가능한 액션 타입들
        $actionTypes = [
            ProjectLog::ACTION_PROJECT_CREATED => '프로젝트 생성',
            ProjectLog::ACTION_PROJECT_UPDATED => '프로젝트 수정',
            ProjectLog::ACTION_PROJECT_DELETED => '프로젝트 삭제',
            ProjectLog::ACTION_PAGE_CREATED => '페이지 생성',
            ProjectLog::ACTION_PAGE_UPDATED => '페이지 수정',
            ProjectLog::ACTION_PAGE_DELETED => '페이지 삭제',
            ProjectLog::ACTION_SETTINGS_UPDATED => '설정 변경',
            ProjectLog::ACTION_USER_ADDED => '사용자 추가',
            ProjectLog::ACTION_USER_REMOVED => '사용자 제거',
            ProjectLog::ACTION_PERMISSION_CHANGED => '권한 변경',
            ProjectLog::ACTION_SANDBOX_CREATED => '샌드박스 생성',
            ProjectLog::ACTION_SANDBOX_UPDATED => '샌드박스 수정',
            ProjectLog::ACTION_SANDBOX_DELETED => '샌드박스 삭제',
        ];

        return view('700-livewire-project-settings-logs', compact('logs', 'project', 'actionTypes'));
    }
}
