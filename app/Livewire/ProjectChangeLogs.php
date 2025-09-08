<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProjectChangeLog;
use Illuminate\Support\Facades\Auth;

class ProjectChangeLogs extends Component
{
    use WithPagination;

    public $projectId;
    public $organizationId;
    public $filterAction = '';
    public $filterUser = '';
    public $dateFrom = '';
    public $dateTo = '';

    protected $paginationTheme = 'tailwind';

    public function mount($projectId, $organizationId)
    {
        $this->projectId = $projectId;
        $this->organizationId = $organizationId;
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
        $this->filterAction = '';
        $this->filterUser = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = ProjectChangeLog::where('project_id', $this->projectId)
            ->with(['user']);

        // 액션 필터
        if ($this->filterAction) {
            $query->where('action', $this->filterAction);
        }

        // 사용자 필터
        if ($this->filterUser) {
            $query->where('user_id', $this->filterUser);
        }

        // 날짜 필터
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        // 액션 목록 (필터 옵션용)
        $actions = ProjectChangeLog::where('project_id', $this->projectId)
            ->distinct()
            ->pluck('action');

        // 사용자 목록 (필터 옵션용)  
        $users = ProjectChangeLog::where('project_id', $this->projectId)
            ->with('user')
            ->get()
            ->pluck('user')
            ->unique('id')
            ->filter();

        return view('livewire.700-livewire-project-change-logs', compact('logs', 'actions', 'users'));
    }

    public function getActionName($action)
    {
        $actionNames = [
            'project_created' => '프로젝트 생성',
            'project_updated' => '프로젝트 정보 수정',
            'project_deleted' => '프로젝트 삭제',
            'page_created' => '페이지 생성',
            'page_updated' => '페이지 수정',
            'page_deleted' => '페이지 삭제',
            'user_added' => '사용자 추가',
            'user_removed' => '사용자 제거',
            'permission_changed' => '권한 변경',
            'settings_changed' => '설정 변경'
        ];

        return $actionNames[$action] ?? $action;
    }
}