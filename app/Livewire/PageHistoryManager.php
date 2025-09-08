<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProjectPage;
use App\Models\ProjectPageDeploymentLog;
use Carbon\Carbon;

class PageHistoryManager extends Component
{
    use WithPagination;
    
    public $pageId;
    public $currentPage;
    public $filterType = 'all'; // all, deployment, content, permissions
    public $dateRange = 'all'; // all, today, week, month
    
    public function mount($pageId)
    {
        $this->pageId = $pageId;
        $this->loadPage();
    }
    
    public function loadPage()
    {
        $this->currentPage = ProjectPage::findOrFail($this->pageId);
    }
    
    public function updatedFilterType()
    {
        $this->resetPage();
    }
    
    public function updatedDateRange()
    {
        $this->resetPage();
    }
    
    public function getDeploymentLogsProperty()
    {
        $query = ProjectPageDeploymentLog::with('user')
            ->where('project_page_id', $this->pageId)
            ->orderBy('created_at', 'desc');
            
        // 변경 타입 필터링
        if ($this->filterType !== 'all') {
            switch ($this->filterType) {
                case 'deployment':
                    $query->where('change_type', 'deployment');
                    break;
                case 'permissions':
                    $query->where('change_type', 'permission');
                    break;
                case 'content':
                    $query->where('change_type', 'content');
                    break;
            }
        }
            
        // 날짜 필터링
        if ($this->dateRange !== 'all') {
            $startDate = $this->getStartDate();
            if ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }
        }
        
        return $query->paginate(10);
    }
    
    private function getStartDate()
    {
        switch ($this->dateRange) {
            case 'today':
                return Carbon::today();
            case 'week':
                return Carbon::now()->startOfWeek();
            case 'month':
                return Carbon::now()->startOfMonth();
            default:
                return null;
        }
    }
    
    public function getStatusLabelProperty()
    {
        return function($status) {
            $labels = [
                // 배포 상태
                'draft' => '초안',
                'review' => '검토 중',
                'published' => '배포됨',
                'archived' => '아카이브됨',
                // 권한 레벨
                'public' => '모든 사용자',
                'members_only' => '조직 멤버만',
                'editors_only' => '편집자 이상',
                'admins_only' => '관리자만',
                'custom' => '사용자 지정'
            ];
            
            return $labels[$status] ?? $status;
        };
    }
    
    public function getStatusColorProperty()
    {
        return function($status) {
            $colors = [
                // 배포 상태
                'draft' => 'gray',
                'review' => 'yellow',
                'published' => 'green',
                'archived' => 'gray',
                // 권한 레벨
                'public' => 'green',
                'members_only' => 'blue',
                'editors_only' => 'purple',
                'admins_only' => 'red',
                'custom' => 'indigo'
            ];
            
            return $colors[$status] ?? 'gray';
        };
    }

    public function getChangeTypeIconProperty()
    {
        return function($changeType) {
            $icons = [
                'deployment' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>',
                'permission' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>',
                'content' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
                'name' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>'
            ];
            
            return $icons[$changeType] ?? $icons['deployment'];
        };
    }

    public function getChangeTypeLabelProperty()
    {
        return function($changeType) {
            $labels = [
                'deployment' => '배포 상태 변경',
                'permission' => '권한 변경',
                'content' => '내용 변경',
                'name' => '페이지명 변경'
            ];
            
            return $labels[$changeType] ?? '변경';
        };
    }

    public function render()
    {
        $logs = $this->deploymentLogs;
        
        return view('page-history-manager', [
            'logs' => $logs
        ]);
    }
}