<?php

namespace App\Livewire\Sandbox\CustomScreens\Live;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ProjectListComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $teamFilter = '';
    public $projects = [];
    public $stats = [];

    public function mount()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.sandbox.custom-screens.live.project-list-component');
    }

    public function loadData()
    {
        try {
            $sandboxConnection = 'sandbox';
            
            // 통계 데이터
            $totalProjects = DB::connection($sandboxConnection)->table('projects')->count();
            $activeProjects = DB::connection($sandboxConnection)->table('projects')->where('status', 'active')->count();
            $pendingProjects = DB::connection($sandboxConnection)->table('projects')->where('status', 'pending')->count();
            $completedProjects = DB::connection($sandboxConnection)->table('projects')->where('status', 'completed')->count();

            $this->stats = [
                'total' => $totalProjects,
                'active' => $activeProjects,
                'pending' => $pendingProjects, 
                'completed' => $completedProjects
            ];
            
            // 프로젝트 목록
            $query = DB::connection($sandboxConnection)
                ->table('projects')
                ->leftJoin('users', 'projects.created_by', '=', 'users.id')
                ->select(
                    'projects.*',
                    'users.name as creator_name'
                );

            if ($this->search) {
                $query->where('projects.name', 'like', '%' . $this->search . '%');
            }

            if ($this->statusFilter) {
                $query->where('projects.status', $this->statusFilter);
            }

            $this->projects = $query
                ->orderBy('projects.created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($project) {
                    return [
                        'id' => $project->id ?? 0,
                        'name' => $project->name ?? '프로젝트',
                        'description' => $project->description ?? '프로젝트 설명',
                        'status' => $project->status ?? 'active',
                        'progress' => rand(10, 100), // 임의 진행률
                        'team' => $this->getRandomTeam(),
                        'deadline' => $project->created_at ? 
                            \Carbon\Carbon::parse($project->created_at)->addDays(30)->format('Y-m-d') :
                            \Carbon\Carbon::now()->addDays(30)->format('Y-m-d'),
                        'creator' => $project->creator_name ?? '관리자'
                    ];
                })
                ->toArray();
                
        } catch (\Exception $e) {
            // 기본 샘플 데이터
            $this->stats = [
                'total' => 24,
                'active' => 18,
                'pending' => 4,
                'completed' => 2
            ];

            $this->projects = [
                [
                    'id' => 1,
                    'name' => '웹 사이트 리뉴얼',
                    'description' => '메인 웹사이트 전면 개편',
                    'status' => 'active',
                    'progress' => 75,
                    'team' => '개발팀',
                    'deadline' => '2025-10-15',
                    'creator' => '홍길동'
                ],
                [
                    'id' => 2,
                    'name' => '모바일 앱 개발',
                    'description' => 'iOS/Android 네이티브 앱',
                    'status' => 'pending',
                    'progress' => 45,
                    'team' => '모바일팀',
                    'deadline' => '2025-12-01',
                    'creator' => '김철수'
                ],
                [
                    'id' => 3,
                    'name' => 'API 플랫폼 구축',
                    'description' => 'RESTful API 서비스 구축',
                    'status' => 'active',
                    'progress' => 90,
                    'team' => '백엔드팀',
                    'deadline' => '2025-09-30',
                    'creator' => '이영희'
                ]
            ];
        }
    }

    private function getRandomTeam()
    {
        $teams = ['개발팀', '디자인팀', '마케팅팀', '기획팀', '백엔드팀', '모바일팀'];
        return $teams[array_rand($teams)];
    }

    public function updatedSearch()
    {
        $this->loadData();
    }

    public function updatedStatusFilter()
    {
        $this->loadData();
    }

    public function updatedTeamFilter()
    {
        $this->loadData();
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'active' => 'green',
            'pending' => 'blue',
            'completed' => 'purple',
            'paused' => 'yellow',
            default => 'gray'
        };
    }

    public function getStatusText($status)
    {
        return match($status) {
            'active' => '진행중',
            'pending' => '계획중',
            'completed' => '완료',
            'paused' => '일시중단',
            default => '상태 미정'
        };
    }
}