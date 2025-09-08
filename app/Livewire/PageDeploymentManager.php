<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProjectPage;
use App\Models\ProjectPageDeploymentLog;
use Illuminate\Support\Facades\Auth;

class PageDeploymentManager extends Component
{
    public $pageId;
    public $currentPage;
    public $deploymentStatus;
    public $changeReason = '';
    
    public function mount($pageId)
    {
        $this->pageId = $pageId;
        $this->loadPage();
    }
    
    public function loadPage()
    {
        $this->currentPage = ProjectPage::findOrFail($this->pageId);
        $this->deploymentStatus = $this->currentPage->status;
    }
    
    public function updateDeploymentStatus()
    {
        $this->validate([
            'deploymentStatus' => 'required|in:draft,review,published,archived',
            'changeReason' => 'nullable|string|max:1000'
        ]);
        
        $oldStatus = $this->currentPage->status;
        
        // 상태 변경
        $this->currentPage->update([
            'status' => $this->deploymentStatus
        ]);
        
        // 배포 로그 기록
        ProjectPageDeploymentLog::create([
            'project_page_id' => $this->pageId,
            'user_id' => 1, // 개발용 - 실제로는 Auth::id() 사용
            'from_status' => $oldStatus,
            'to_status' => $this->deploymentStatus,
            'reason' => $this->changeReason
        ]);
        
        $this->changeReason = '';
        
        session()->flash('message', '배포 상태가 성공적으로 변경되었습니다.');
        $this->loadPage();
    }
    
    public function getStatusLabelProperty()
    {
        $statusLabels = [
            'draft' => '초안',
            'review' => '검토 중', 
            'published' => '배포됨',
            'archived' => '아카이브됨'
        ];
        
        return $statusLabels[$this->currentPage->status] ?? '알 수 없음';
    }
    
    public function getStatusColorProperty()
    {
        $statusColors = [
            'draft' => 'gray',
            'review' => 'yellow',
            'published' => 'green',
            'archived' => 'gray'
        ];
        
        return $statusColors[$this->currentPage->status] ?? 'gray';
    }

    public function render()
    {
        return view('page-deployment-manager');
    }
}