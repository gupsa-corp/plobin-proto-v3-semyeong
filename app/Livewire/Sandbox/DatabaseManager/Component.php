<?php

namespace App\Livewire\Sandbox\DatabaseManager;

use Livewire\Component as LivewireComponent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Livewire\WithPagination;

class Component extends LivewireComponent
{
    use WithPagination;
    
    public $selectedTable = null;
    public $tableData = [];
    public $columns = [];
    public $perPage = 25;
    public $search = '';
    public $sortField = '';
    public $sortDirection = 'asc';
    
    protected $listeners = ['tableSelected'];
    
    public function mount()
    {
        $this->setupSandboxDatabase();
    }
    
    private function setupSandboxDatabase()
    {
        $selectedSandbox = Session::get('sandbox_storage', '1');
        $sandboxDbPath = storage_path("storage-sandbox-{$selectedSandbox}/db/ai.sqlite");
        
        if (file_exists($sandboxDbPath)) {
            Config::set('database.connections.sandbox_sqlite', [
                'driver' => 'sqlite',
                'database' => $sandboxDbPath,
                'prefix' => '',
                'foreign_key_constraints' => true,
            ]);
        }
    }
    
    private function getSandboxConnection()
    {
        $selectedSandbox = Session::get('sandbox_storage', '1');
        $sandboxDbPath = storage_path("storage-sandbox-{$selectedSandbox}/db/ai.sqlite");
        
        if (file_exists($sandboxDbPath)) {
            // 매번 연결 설정을 새로 생성
            Config::set('database.connections.sandbox_sqlite', [
                'driver' => 'sqlite',
                'database' => $sandboxDbPath,
                'prefix' => '',
                'foreign_key_constraints' => true,
            ]);
            
            // DB 매니저 재설정
            DB::purge('sandbox_sqlite');
            
            return DB::connection('sandbox_sqlite');
        }
        
        return null;
    }
    
    public function render()
    {
        $tables = $this->getDatabaseTables();
        $paginatedData = $this->getPaginatedData();
        $selectedSandbox = Session::get('sandbox_storage', '1');
        
        return view('700-page-sandbox.705-livewire-database-manager', [
            'tables' => $tables,
            'paginatedData' => $paginatedData,
            'selectedSandbox' => $selectedSandbox
        ]);
    }
    
    public function getDatabaseTables()
    {
        try {
            $connection = $this->getSandboxConnection();
            
            if (!$connection) {
                session()->flash('error', '선택된 샌드박스의 데이터베이스에 연결할 수 없습니다.');
                return [];
            }
            
            $driver = $connection->getDriverName();
            
            $tableNames = [];
            
            switch ($driver) {
                case 'sqlite':
                    $tables = $connection->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                    foreach ($tables as $table) {
                        $tableNames[] = $table->name;
                    }
                    break;
                    
                case 'mysql':
                    $tables = Schema::getAllTables();
                    foreach ($tables as $table) {
                        $tableName = $table->Tables_in_plobin_proto_v3 ?? 
                                    $table->{'Tables_in_' . config('database.connections.mysql.database')} ?? 
                                    array_values((array) $table)[0];
                        $tableNames[] = $tableName;
                    }
                    break;
                    
                case 'pgsql':
                    $tables = $connection->select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
                    foreach ($tables as $table) {
                        $tableNames[] = $table->tablename;
                    }
                    break;
                    
                default:
                    // 기본적으로 information_schema 사용
                    $tables = $connection->select("SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()");
                    foreach ($tables as $table) {
                        $tableNames[] = $table->table_name;
                    }
                    break;
            }
            
            return collect($tableNames)->sort()->values()->all();
            
        } catch (\Exception $e) {
            session()->flash('error', '테이블 목록 조회 실패: ' . $e->getMessage());
            return [];
        }
    }
    
    public function selectTable($tableName)
    {
        $this->selectedTable = $tableName;
        $this->loadTableData();
        $this->resetPage();
        $this->search = '';
        $this->sortField = '';
        $this->sortDirection = 'asc';
    }
    
    public function loadTableData()
    {
        if (!$this->selectedTable) {
            return;
        }
        
        try {
            $connection = $this->getSandboxConnection();
            
            if (!$connection) {
                $this->columns = [];
                session()->flash('error', '선택된 샌드박스의 데이터베이스에 연결할 수 없습니다.');
                return;
            }
            
            // 테이블 컬럼 정보 가져오기
            $this->columns = Schema::connection('sandbox_sqlite')->getColumnListing($this->selectedTable);
                
        } catch (\Exception $e) {
            $this->columns = [];
            session()->flash('error', '테이블 데이터 로드 실패: ' . $e->getMessage());
        }
    }
    
    public function getPaginatedData()
    {
        if (!$this->selectedTable || empty($this->columns)) {
            return null;
        }
        
        try {
            $connection = $this->getSandboxConnection();
            
            if (!$connection) {
                session()->flash('error', '선택된 샌드박스의 데이터베이스에 연결할 수 없습니다.');
                return null;
            }
            
            $query = $connection->table($this->selectedTable);
            
            // 검색 기능
            if (!empty($this->search)) {
                $query->where(function($q) {
                    foreach ($this->columns as $column) {
                        $q->orWhere($column, 'LIKE', '%' . $this->search . '%');
                    }
                });
            }
            
            // 정렬 기능
            if (!empty($this->sortField) && in_array($this->sortField, $this->columns)) {
                $query->orderBy($this->sortField, $this->sortDirection);
            }
            
            return $query->paginate($this->perPage);
            
        } catch (\Exception $e) {
            session()->flash('error', '데이터 조회 실패: ' . $e->getMessage());
            return null;
        }
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedPerPage()
    {
        $this->resetPage();
    }
}