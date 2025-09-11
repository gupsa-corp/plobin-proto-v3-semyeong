<?php

namespace App\Livewire\Sandbox\CustomScreens\Live;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DashboardComponent extends Component
{
    public $stats = [];
    public $recentActivities = [];
    public $systemStatus = [];

    public function mount()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.sandbox.custom-screens.live.dashboard-component');
    }

    public function loadData()
    {
        try {
            $sandboxConnection = 'sandbox';
            
            // 통계 데이터
            $this->stats = [
                'total_organizations' => DB::connection($sandboxConnection)->table('organizations')->count(),
                'total_projects' => DB::connection($sandboxConnection)->table('projects')->count(),
                'total_users' => DB::connection($sandboxConnection)->table('users')->count()
            ];
            
            // 최근 활동 (프로젝트 생성 기준)
            $this->recentActivities = DB::connection($sandboxConnection)
                ->table('projects')
                ->join('users', 'projects.created_by', '=', 'users.id')
                ->select('projects.name as project_name', 'users.name as user_name', 'projects.created_at')
                ->orderBy('projects.created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($activity) {
                    return [
                        'action' => '새 프로젝트 생성',
                        'project' => $activity->project_name ?? '프로젝트',
                        'user' => $activity->user_name ?? '사용자',
                        'time' => $activity->created_at ? \Carbon\Carbon::parse($activity->created_at)->diffForHumans() : '방금 전'
                    ];
                })
                ->toArray();
            
            // 시스템 상태 (예시)
            $this->systemStatus = [
                ['name' => '서버 상태', 'status' => 'normal', 'color' => 'green'],
                ['name' => '데이터베이스', 'status' => 'warning', 'color' => 'yellow'], 
                ['name' => 'API 서비스', 'status' => 'normal', 'color' => 'green']
            ];
            
        } catch (\Exception $e) {
            // 기본값 설정
            $this->stats = [
                'total_organizations' => 3,
                'total_projects' => 12,
                'total_users' => 25
            ];
            
            $this->recentActivities = [
                ['action' => '새 프로젝트 생성', 'project' => '웹사이트 리뉴얼', 'user' => '홍길동', 'time' => '2시간 전'],
                ['action' => '커스텀 화면 업데이트', 'project' => '모바일 앱', 'user' => '김철수', 'time' => '5시간 전'],
                ['action' => '새 사용자 가입', 'project' => 'API 플랫폼', 'user' => '이영희', 'time' => '1일 전']
            ];
            
            $this->systemStatus = [
                ['name' => '서버 상태', 'status' => 'normal', 'color' => 'green'],
                ['name' => '데이터베이스', 'status' => 'warning', 'color' => 'yellow'], 
                ['name' => 'API 서비스', 'status' => 'normal', 'color' => 'green']
            ];
        }
    }

    public function refreshData()
    {
        $this->loadData();
        $this->dispatch('data-refreshed');
    }
}