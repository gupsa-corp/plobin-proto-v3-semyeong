<?php

namespace App\Livewire\Sandbox\SqlExecutor;

use Livewire\Component as LivewireComponent;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\SandboxSqlExecution;
use Livewire\WithPagination;

class Component extends LivewireComponent
{
    use WithPagination;

    public $sqlQuery = '';
    public $executionResult = null;
    public $isExecuting = false;
    public $showHistory = false;

    protected $listeners = ['sqlExecuted'];

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
            Config::set('database.connections.sandbox_sqlite', [
                'driver' => 'sqlite',
                'database' => $sandboxDbPath,
                'prefix' => '',
                'foreign_key_constraints' => true,
            ]);

            DB::purge('sandbox_sqlite');
            return DB::connection('sandbox_sqlite');
        }

        return null;
    }

    public function executeSql()
    {
        if (empty(trim($this->sqlQuery))) {
            session()->flash('error', 'SQL 쿼리를 입력해주세요.');
            return;
        }

        $this->isExecuting = true;
        $startTime = microtime(true);
        $selectedSandbox = Session::get('sandbox_storage', '1');

        try {
            $connection = $this->getSandboxConnection();

            if (!$connection) {
                throw new \Exception('선택된 샌드박스의 데이터베이스에 연결할 수 없습니다.');
            }

            $queryType = SandboxSqlExecution::getQueryType($this->sqlQuery);

            // SQL 실행
            if (strtoupper(trim($this->sqlQuery)) === 'SELECT' || stripos($this->sqlQuery, 'SELECT') === 0) {
                // SELECT 쿼리
                $results = $connection->select($this->sqlQuery);
                $affectedRows = count($results);
                $resultData = array_map(function($row) {
                    return (array) $row;
                }, $results);
            } else {
                // INSERT, UPDATE, DELETE 등
                $affectedRows = $connection->statement($this->sqlQuery);
                $resultData = ['message' => "쿼리가 성공적으로 실행되었습니다. 영향받은 행: {$affectedRows}"];
            }

            $executionTime = round((microtime(true) - $startTime) * 1000);

            // 실행 결과 저장
            $this->executionResult = [
                'status' => 'success',
                'data' => $resultData,
                'affected_rows' => $affectedRows,
                'execution_time' => $executionTime,
                'query_type' => $queryType
            ];

            // 메인 DB에 실행 기록 저장
            SandboxSqlExecution::logExecution(
                "storage-sandbox-{$selectedSandbox}",
                $this->sqlQuery,
                $queryType,
                'success',
                $resultData,
                null,
                $affectedRows,
                $executionTime
            );

            session()->flash('success', "SQL 실행 완료! ({$executionTime}ms, {$affectedRows}행 영향)");

        } catch (\Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000);
            $queryType = SandboxSqlExecution::getQueryType($this->sqlQuery);

            // 에러 결과 저장
            $this->executionResult = [
                'status' => 'error',
                'error' => $e->getMessage(),
                'execution_time' => $executionTime,
                'query_type' => $queryType
            ];

            // 메인 DB에 에러 기록 저장
            SandboxSqlExecution::logExecution(
                "storage-sandbox-{$selectedSandbox}",
                $this->sqlQuery,
                $queryType,
                'error',
                null,
                $e->getMessage(),
                null,
                $executionTime
            );

            session()->flash('error', '실행 오류: ' . $e->getMessage());
        }

        $this->isExecuting = false;
    }

    public function clearQuery()
    {
        $this->sqlQuery = '';
        $this->executionResult = null;
        $this->resetPage();
    }

    public function toggleHistory()
    {
        $this->showHistory = !$this->showHistory;
        $this->resetPage();
    }

    public function loadExample($example)
    {
        $examples = [
            'select' => 'SELECT * FROM users LIMIT 5;',
            'insert' => "INSERT INTO users (name, email) VALUES ('테스트 사용자', 'test@example.com');",
            'update' => "UPDATE users SET name = '수정된 이름' WHERE id = 1;",
            'create' => "CREATE TABLE test_new_table (id INTEGER PRIMARY KEY, name TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);",
            'drop' => 'DROP TABLE test_new_table;'
        ];

        $this->sqlQuery = $examples[$example] ?? '';
    }

    public function getExecutionHistory()
    {
        $selectedSandbox = Session::get('sandbox_storage', '1');
        
        return SandboxSqlExecution::where('sandbox_name', "storage-sandbox-{$selectedSandbox}")
            ->where('user_session_id', session()->getId())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        $selectedSandbox = Session::get('sandbox_storage', '1');
        $executionHistory = $this->showHistory ? $this->getExecutionHistory() : null;

        return view('700-page-sandbox.702-livewire-sql-executor', [
            'selectedSandbox' => $selectedSandbox,
            'executionHistory' => $executionHistory
        ]);
    }
}