<?php

namespace App\Livewire\Sandbox;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Manager extends Component
{
    public string $currentPath = 'files/views';
    public string $content = '';
    public string $fileName = '';
    public array $list = [];
    public string $activeTab = 'files'; // files, sql, tables, execute
    public string $sqlQuery = '';
    public array $sqlResult = [];
    public array $sqlHistory = [];
    public array $tableList = [];
    public string $selectedTable = '';
    public array $tableSchema = [];
    public string $executeType = 'view'; // view, controller, livewire
    public string $executePath = '';
    public array $executeResult = [];

    protected $disk;

    public function mount()
    {
        $this->refreshList();
    }

    protected function getDisk()
    {
        if (!$this->disk) {
            $this->disk = Storage::disk('local');
        }
        return $this->disk;
    }

    public function refreshList()
    {
        $sandboxPath = 'ai-sandbox/' . $this->currentPath;
        $disk = $this->getDisk();

        if ($disk->exists($sandboxPath)) {
            $this->list = [
                'dirs' => $disk->directories($sandboxPath),
                'files' => $disk->files($sandboxPath)
            ];

            // 경로를 상대경로로 변환
            $this->list['dirs'] = array_map(fn($dir) => str_replace('ai-sandbox/', '', $dir), $this->list['dirs']);
            $this->list['files'] = array_map(fn($file) => str_replace('ai-sandbox/', '', $file), $this->list['files']);
        } else {
            $this->list = ['dirs' => [], 'files' => []];
        }
    }

    public function selectDirectory($dir)
    {
        $this->currentPath = $dir;
        $this->content = '';
        $this->fileName = '';
        $this->refreshList();
    }

    public function selectFile($file)
    {
        $this->fileName = basename($file);
        $sandboxFile = 'ai-sandbox/' . $file;
        $disk = $this->getDisk();

        if ($disk->exists($sandboxFile)) {
            $this->content = $disk->get($sandboxFile);
        } else {
            $this->content = '';
        }
    }

    public function saveFile()
    {
        if (empty($this->fileName)) {
            session()->flash('error', '파일명을 입력해주세요.');
            return;
        }

        $filePath = $this->currentPath . '/' . $this->fileName;
        $sandboxFile = 'ai-sandbox/' . $filePath;
        $disk = $this->getDisk();

        // 디렉토리 생성
        $disk->makeDirectory(dirname($sandboxFile));

        // 파일 저장
        $disk->put($sandboxFile, $this->content);

        session()->flash('message', '파일이 저장되었습니다: ' . $filePath);
        $this->refreshList();
    }

    public function deleteFile($file)
    {
        $sandboxFile = 'ai-sandbox/' . $file;
        $disk = $this->getDisk();

        if ($disk->exists($sandboxFile)) {
            $disk->delete($sandboxFile);
            session()->flash('message', '파일이 삭제되었습니다: ' . $file);
            $this->refreshList();

            // 현재 편집중인 파일이면 초기화
            if ($this->fileName === basename($file)) {
                $this->content = '';
                $this->fileName = '';
            }
        }
    }

    public function executeSql()
    {
        try {
            // AI 샌드박스 전용 SQLite 사용
            $this->sqlResult = \DB::connection('ai_sandbox')->select($this->sqlQuery);

            // 쿼리 히스토리에 추가
            $this->addToHistory($this->sqlQuery);

            session()->flash('sql-message', 'SQL 쿼리가 실행되었습니다. (AI 샌드박스 SQLite)');
        } catch (\Exception $e) {
            $this->sqlResult = [];

            // 오류가 있어도 히스토리에 추가 (오류 표시와 함께)
            $this->addToHistory($this->sqlQuery, $e->getMessage());

            session()->flash('sql-error', 'SQL 실행 오류: ' . $e->getMessage());
        }
    }

    protected function addToHistory($query, $error = null)
    {
        $historyItem = [
            'query' => $query,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'error' => $error
        ];

        // 최대 20개 히스토리 유지
        array_unshift($this->sqlHistory, $historyItem);
        if (count($this->sqlHistory) > 20) {
            $this->sqlHistory = array_slice($this->sqlHistory, 0, 20);
        }
    }

    public function loadTables()
    {
        try {
            // SQLite에서 테이블 목록 조회
            $tables = \DB::connection('ai_sandbox')->select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
            $this->tableList = collect($tables)->pluck('name')->toArray();
        } catch (\Exception $e) {
            $this->tableList = [];
            session()->flash('table-error', '테이블 목록 조회 오류: ' . $e->getMessage());
        }
    }

    public function selectTable($tableName)
    {
        $this->selectedTable = $tableName;
        $this->loadTableSchema($tableName);
    }

    protected function loadTableSchema($tableName)
    {
        try {
            // 테이블 스키마 조회
            $schema = \DB::connection('ai_sandbox')->select("PRAGMA table_info($tableName)");
            $this->tableSchema = $schema;
        } catch (\Exception $e) {
            $this->tableSchema = [];
            session()->flash('table-error', '테이블 스키마 조회 오류: ' . $e->getMessage());
        }
    }

    public function executeHistoryQuery($index)
    {
        if (isset($this->sqlHistory[$index])) {
            $this->sqlQuery = $this->sqlHistory[$index]['query'];
            $this->executeSql();
        }
    }

    public function executeCode()
    {
        $this->executeResult = [];

        try {
            switch ($this->executeType) {
                case 'view':
                    $this->executeResult = ['message' => 'View 렌더링 기능 구현 예정'];
                    break;

                case 'controller':
                    $this->executeResult = ['message' => 'Controller 실행 기능 구현 예정'];
                    break;

                case 'livewire':
                    $this->executeResult = ['message' => 'Livewire 컴포넌트 실행 기능 구현 예정'];
                    break;
            }

            session()->flash('execute-message', $this->executeType . ' 실행이 완료되었습니다.');
        } catch (\Exception $e) {
            $this->executeResult = ['error' => $e->getMessage()];
            session()->flash('execute-error', '실행 오류: ' . $e->getMessage());
        }
    }

    public function quickDataQuery()
    {
        $this->activeTab = 'sql';
        $this->sqlQuery = "SELECT * FROM {$this->selectedTable} LIMIT 10";
    }

    public function quickCountQuery()
    {
        $this->activeTab = 'sql';
        $this->sqlQuery = "SELECT COUNT(*) as total FROM {$this->selectedTable}";
    }

    public function quickSchemaQuery()
    {
        $this->activeTab = 'sql';
        $this->sqlQuery = "PRAGMA table_info({$this->selectedTable})";
    }

    public function render()
    {
        return view('livewire.sandbox.900-livewire-sandbox-manager');
    }
}
