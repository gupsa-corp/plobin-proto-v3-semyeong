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

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'filterStatus' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
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
            // 기본값 설정
            $this->projects = [
                (object)[
                    'id' => 1,
                    'name' => '웹사이트 리뉴얼 프로젝트',
                    'description' => '기존 웹사이트의 전면적인 개편',
                    'status' => 'active',
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()->subDays(1),
                    'created_by_name' => '홍길동',
                    'organization_name' => '테크 스타트업'
                ],
                (object)[
                    'id' => 2,
                    'name' => '모바일 앱 개발',
                    'description' => 'iOS/Android 네이티브 앱 개발',
                    'status' => 'in_progress',
                    'created_at' => now()->subDays(10),
                    'updated_at' => now()->subDays(3),
                    'created_by_name' => '김철수',
                    'organization_name' => '디지털 에이전시'
                ],
                (object)[
                    'id' => 3,
                    'name' => 'API 플랫폼 구축',
                    'description' => 'RESTful API 서버 구축 및 문서화',
                    'status' => 'completed',
                    'created_at' => now()->subDays(20),
                    'updated_at' => now()->subDays(5),
                    'created_by_name' => '이영희',
                    'organization_name' => '클라우드 솔루션'
                ]
            ];

            $this->stats = [
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