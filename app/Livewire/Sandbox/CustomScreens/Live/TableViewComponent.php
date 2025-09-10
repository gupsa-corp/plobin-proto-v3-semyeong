<?php

namespace App\Livewire\Sandbox\CustomScreens\Live;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class TableViewComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 15;
    public $filterStatus = '';
    
    public $projects = [];
    public $stats = [];
    public $screenId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'filterStatus' => ['except' => ''],
        'screen' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        // URL에서 screen 파라미터 가져오기
        $this->screenId = request()->get('screen', '');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.sandbox.custom-screens.live.table-view-component');
    }

    public function loadData()
    {
        try {
            $sandboxConnection = 'sandbox';
            
            $query = DB::connection($sandboxConnection)
                ->table('projects')
                ->leftJoin('users', 'projects.created_by', '=', 'users.id')
                ->leftJoin('organizations', 'projects.organization_id', '=', 'organizations.id')
                ->select(
                    'projects.*',
                    'users.name as created_by_name',
                    'organizations.name as organization_name'
                );

            // 검색 적용
            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('projects.name', 'like', '%' . $this->search . '%')
                      ->orWhere('projects.description', 'like', '%' . $this->search . '%')
                      ->orWhere('users.name', 'like', '%' . $this->search . '%');
                });
            }

            // 상태 필터 적용
            if (!empty($this->filterStatus)) {
                $query->where('projects.status', $this->filterStatus);
            }

            // 정렬 적용
            $query->orderBy($this->sortBy, $this->sortDirection);

            $this->projects = $query->paginate($this->perPage)->items();

            // 통계 데이터
            $this->stats = [
                'total_projects' => DB::connection($sandboxConnection)->table('projects')->count(),
                'active_projects' => DB::connection($sandboxConnection)->table('projects')->where('status', 'active')->count(),
                'completed_projects' => DB::connection($sandboxConnection)->table('projects')->where('status', 'completed')->count(),
                'total_organizations' => DB::connection($sandboxConnection)->table('organizations')->count()
            ];
            
        } catch (\Exception $e) {
            // screen 파라미터에 따라 다른 데이터 세트 제공
            $this->projects = $this->getProjectDataForScreen();
            $this->stats = $this->getStatsDataForScreen();
        }
    }

    /**
     * Screen ID에 따라 다른 프로젝트 데이터 반환
     */
    private function getProjectDataForScreen()
    {
        // 기본 데이터 세트 (screen 파라미터가 2059a206aa5bcf8f404e5ae486859b73 또는 빈값)
        $defaultProjects = [
            (object)[
                'id' => 1,
                'name' => '프로젝트 1',
                'description' => '프로젝트 1에 대한 상세 설명입니다. 이 프로젝트는 현재 완료 상태입니다.',
                'status' => '완료',
                'progress' => 59,
                'team_members' => 8,
                'created_at' => '2025-08-25',
                'updated_at' => now()->subDays(1),
                'created_by_name' => '홍길동',
                'organization_name' => '테크 스타트업'
            ],
            (object)[
                'id' => 2,
                'name' => '프로젝트 2',
                'description' => '프로젝트 2에 대한 상세 설명입니다. 이 프로젝트는 현재 진행 중 상태입니다.',
                'status' => '진행 중',
                'progress' => 44,
                'team_members' => 7,
                'created_at' => '2025-09-07',
                'updated_at' => now()->subDays(3),
                'created_by_name' => '김철수',
                'organization_name' => '디지털 에이전시'
            ],
            (object)[
                'id' => 3,
                'name' => '프로젝트 3',
                'description' => '프로젝트 3에 대한 상세 설명입니다. 이 프로젝트는 현재 완료 상태입니다.',
                'status' => '완료',
                'progress' => 43,
                'team_members' => 5,
                'created_at' => '2025-08-27',
                'updated_at' => now()->subDays(5),
                'created_by_name' => '이영희',
                'organization_name' => '클라우드 솔루션'
            ],
            (object)[
                'id' => 4,
                'name' => '프로젝트 4',
                'description' => '프로젝트 4에 대한 상세 설명입니다. 이 프로젝트는 현재 진행 중 상태입니다.',
                'status' => '진행 중',
                'progress' => 75,
                'team_members' => 8,
                'created_at' => '2025-08-26',
                'updated_at' => now()->subDays(2),
                'created_by_name' => '박민수',
                'organization_name' => '스타트업 허브'
            ],
            (object)[
                'id' => 5,
                'name' => '프로젝트 5',
                'description' => '프로젝트 5에 대한 상세 설명입니다. 이 프로젝트는 현재 계획 상태입니다.',
                'status' => '계획',
                'progress' => 85,
                'team_members' => 5,
                'created_at' => '2025-08-24',
                'updated_at' => now()->subDays(4),
                'created_by_name' => '최지연',
                'organization_name' => '이노베이션 랩'
            ],
        ];

        // 대체 데이터 세트 (screen=01c4f4304b6bd4325479dc32037e6cf0)
        $alternativeProjects = [
            (object)[
                'id' => 1,
                'name' => '프로젝트 1',
                'description' => '프로젝트 1에 대한 상세 설명입니다. 이 프로젝트는 현재 보류 상태입니다.',
                'status' => '보류',
                'progress' => 26,
                'team_members' => 8,
                'created_at' => '2025-08-16',
                'updated_at' => now()->subDays(1),
                'created_by_name' => '홍길동',
                'organization_name' => '테크 스타트업'
            ],
            (object)[
                'id' => 2,
                'name' => '프로젝트 2',
                'description' => '프로젝트 2에 대한 상세 설명입니다. 이 프로젝트는 현재 완료 상태입니다.',
                'status' => '완료',
                'progress' => 74,
                'team_members' => 5,
                'created_at' => '2025-08-29',
                'updated_at' => now()->subDays(3),
                'created_by_name' => '김철수',
                'organization_name' => '디지털 에이전시'
            ],
            (object)[
                'id' => 3,
                'name' => '프로젝트 3',
                'description' => '프로젝트 3에 대한 상세 설명입니다. 이 프로젝트는 현재 계획 상태입니다.',
                'status' => '계획',
                'progress' => 100,
                'team_members' => 7,
                'created_at' => '2025-08-19',
                'updated_at' => now()->subDays(5),
                'created_by_name' => '이영희',
                'organization_name' => '클라우드 솔루션'
            ],
            (object)[
                'id' => 4,
                'name' => '프로젝트 4',
                'description' => '프로젝트 4에 대한 상세 설명입니다. 이 프로젝트는 현재 완료 상태입니다.',
                'status' => '완료',
                'progress' => 53,
                'team_members' => 2,
                'created_at' => '2025-08-12',
                'updated_at' => now()->subDays(2),
                'created_by_name' => '박민수',
                'organization_name' => '스타트업 허브'
            ],
            (object)[
                'id' => 5,
                'name' => '프로젝트 5',
                'description' => '프로젝트 5에 대한 상세 설명입니다. 이 프로젝트는 현재 진행 중 상태입니다.',
                'status' => '진행 중',
                'progress' => 42,
                'team_members' => 8,
                'created_at' => '2025-08-17',
                'updated_at' => now()->subDays(4),
                'created_by_name' => '최지연',
                'organization_name' => '이노베이션 랩'
            ],
        ];

        // Screen ID에 따라 다른 데이터 반환
        if ($this->screenId === '01c4f4304b6bd4325479dc32037e6cf0') {
            return $alternativeProjects;
        } else {
            // 기본값 또는 screen=2059a206aa5bcf8f404e5ae486859b73
            return $defaultProjects;
        }
    }

    /**
     * Screen ID에 따라 다른 통계 데이터 반환
     */
    private function getStatsDataForScreen()
    {
        if ($this->screenId === '01c4f4304b6bd4325479dc32037e6cf0') {
            // 대체 데이터의 통계
            return [
                'total_projects' => 15,
                'active_projects' => 6,
                'completed_projects' => 7,
                'total_organizations' => 4
            ];
        } else {
            // 기본 데이터의 통계
            return [
                'total_projects' => 15,
                'active_projects' => 8,
                'completed_projects' => 5,
                'total_organizations' => 3
            ];
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->loadData();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
        $this->loadData();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->loadData();
    }

    public function refreshData()
    {
        $this->loadData();
        $this->dispatch('data-refreshed');
    }
}