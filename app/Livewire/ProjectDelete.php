<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectPage;
use App\Models\ProjectChangeLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectDelete extends Component
{
    public $projectId;
    public $organizationId;
    public $confirmText = '';
    public $project;

    public function mount($projectId, $organizationId)
    {
        $this->projectId = $projectId;
        $this->organizationId = $organizationId;
        $this->project = Project::findOrFail($projectId);
    }

    public function getCanDeleteProperty()
    {
        // 확인 텍스트가 정확히 입력되었고, 페이지가 모두 삭제되었을 때만 삭제 가능
        return $this->confirmText === '삭제' && $this->getRemainingPagesCount() === 0;
    }

    public function getRemainingPagesCount()
    {
        return ProjectPage::where('project_id', $this->projectId)
            ->whereNull('deleted_at')
            ->count();
    }

    public function getRemainingPages()
    {
        return ProjectPage::where('project_id', $this->projectId)
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get();
    }

    public function deleteProject()
    {
        // 페이지가 남아있는지 확인
        $remainingPagesCount = $this->getRemainingPagesCount();
        if ($remainingPagesCount > 0) {
            session()->flash('error', "프로젝트를 삭제하기 전에 모든 페이지를 삭제해야 합니다. ({$remainingPagesCount}개 페이지 남음)");
            return;
        }

        // 확인 텍스트 검증
        if ($this->confirmText !== '삭제') {
            session()->flash('error', '삭제를 확인하려면 "삭제"를 정확히 입력하세요.');
            return;
        }

        try {
            DB::transaction(function () {
                // 변경 로그 기록
                ProjectChangeLog::logChange(
                    $this->projectId,
                    'project_deleted',
                    "프로젝트 '{$this->project->name}'가 삭제되었습니다.",
                    [
                        'project_id' => $this->project->id,
                        'project_name' => $this->project->name,
                        'deleted_by' => Auth::id()
                    ]
                );
                
                // 프로젝트 자체를 소프트 삭제
                $this->project->delete();
            });

            session()->flash('message', '프로젝트가 성공적으로 삭제되었습니다.');
            
            // 조직 대시보드로 리다이렉트
            return redirect()->route('organization.dashboard', ['id' => $this->organizationId]);
            
        } catch (\Exception $e) {
            session()->flash('error', '프로젝트 삭제 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.700-livewire-project-delete');
    }
}