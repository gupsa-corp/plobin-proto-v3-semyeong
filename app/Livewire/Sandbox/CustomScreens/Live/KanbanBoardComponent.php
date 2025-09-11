<?php

namespace App\Livewire\Sandbox\CustomScreens\Live;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class KanbanBoardComponent extends Component
{
    public $columns = [];
    public $projects = [];
    public $stats = [];

    protected $listeners = ['projectMoved' => 'handleProjectMove'];

    public function mount()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.sandbox.custom-screens.live.kanban-board-component');
    }

    public function loadData()
    {
        try {
            $sandboxConnection = 'sandbox';
            
            // 칼럼 정의
            $this->columns = [
                ['id' => 'backlog', 'name' => '백로그', 'color' => 'gray'],
                ['id' => 'todo', 'name' => '할 일', 'color' => 'blue'],
                ['id' => 'in_progress', 'name' => '진행 중', 'color' => 'yellow'],
                ['id' => 'review', 'name' => '검토', 'color' => 'purple'],
                ['id' => 'done', 'name' => '완료', 'color' => 'green']
            ];

            // 프로젝트 데이터 로드
            $allProjects = DB::connection($sandboxConnection)
                ->table('projects')
                ->leftJoin('users', 'projects.created_by', '=', 'users.id')
                ->leftJoin('organizations', 'projects.organization_id', '=', 'organizations.id')
                ->select(
                    'projects.*',
                    'users.name as created_by_name',
                    'organizations.name as organization_name'
                )
                ->get();

            // 상태별로 프로젝트 그룹화
            $this->projects = [];
            foreach ($this->columns as $column) {
                $this->projects[$column['id']] = $allProjects->filter(function ($project) use ($column) {
                    return $this->mapStatusToColumn($project->status ?? 'backlog') === $column['id'];
                })->values()->toArray();
            }

            // 통계 데이터
            $this->stats = [
                'total_projects' => $allProjects->count(),
                'in_progress' => $allProjects->where('status', 'in_progress')->count(),
                'completed' => $allProjects->where('status', 'completed')->count(),
                'blocked' => $allProjects->where('status', 'blocked')->count()
            ];
            
        } catch (\Exception $e) {
            // 기본값 설정
            $this->columns = [
                ['id' => 'backlog', 'name' => '백로그', 'color' => 'gray'],
                ['id' => 'todo', 'name' => '할 일', 'color' => 'blue'],
                ['id' => 'in_progress', 'name' => '진행 중', 'color' => 'yellow'],
                ['id' => 'review', 'name' => '검토', 'color' => 'purple'],
                ['id' => 'done', 'name' => '완료', 'color' => 'green']
            ];

            $sampleProjects = [
                (object)[
                    'id' => 1,
                    'name' => '웹사이트 리뉴얼',
                    'description' => '기존 웹사이트의 전면적인 개편 작업',
                    'status' => 'in_progress',
                    'created_by_name' => '홍길동',
                    'organization_name' => '테크 스타트업',
                    'created_at' => now()->subDays(5)
                ],
                (object)[
                    'id' => 2,
                    'name' => '모바일 앱 개발',
                    'description' => 'iOS/Android 네이티브 앱 개발',
                    'status' => 'todo',
                    'created_by_name' => '김철수',
                    'organization_name' => '디지털 에이전시',
                    'created_at' => now()->subDays(3)
                ],
                (object)[
                    'id' => 3,
                    'name' => 'API 플랫폼 구축',
                    'description' => 'RESTful API 서버 구축',
                    'status' => 'done',
                    'created_by_name' => '이영희',
                    'organization_name' => '클라우드 솔루션',
                    'created_at' => now()->subDays(15)
                ],
                (object)[
                    'id' => 4,
                    'name' => '데이터베이스 최적화',
                    'description' => '쿼리 성능 개선 및 인덱스 최적화',
                    'status' => 'review',
                    'created_by_name' => '박민수',
                    'organization_name' => '테크 스타트업',
                    'created_at' => now()->subDays(7)
                ]
            ];

            $this->projects = [];
            foreach ($this->columns as $column) {
                $this->projects[$column['id']] = array_filter($sampleProjects, function ($project) use ($column) {
                    return $this->mapStatusToColumn($project->status) === $column['id'];
                });
            }

            $this->stats = [
                'total_projects' => 8,
                'in_progress' => 3,
                'completed' => 2,
                'blocked' => 1
            ];
        }
    }

    private function mapStatusToColumn($status)
    {
        $mapping = [
            'backlog' => 'backlog',
            'todo' => 'todo',
            'active' => 'todo',
            'in_progress' => 'in_progress',
            'review' => 'review',
            'testing' => 'review',
            'completed' => 'done',
            'done' => 'done'
        ];

        return $mapping[$status] ?? 'backlog';
    }

    public function handleProjectMove($projectId, $fromColumn, $toColumn)
    {
        // 실제 구현에서는 데이터베이스 업데이트 로직 추가
        $this->dispatch('project-moved', [
            'projectId' => $projectId,
            'fromColumn' => $fromColumn,
            'toColumn' => $toColumn
        ]);
        
        $this->loadData();
    }

    public function refreshData()
    {
        $this->loadData();
        $this->dispatch('data-refreshed');
    }
}