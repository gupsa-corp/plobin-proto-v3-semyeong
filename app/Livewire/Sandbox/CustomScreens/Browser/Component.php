<?php

namespace App\Livewire\Sandbox\CustomScreens\Browser;

use Livewire\Component as LivewireComponent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class Component extends LivewireComponent
{
    public $screens = [];
    public $search = '';
    public $filterType = '';
    public $selectedScreen = null;
    public $previewMode = false;
    public $currentStorage = '';

    public function mount()
    {
        $this->currentStorage = Session::get('sandbox_storage', 'template');
        $this->loadScreens();
    }

    public function render()
    {
        return view('livewire.sandbox.custom-screens.browser-component');
    }

    public function loadScreens()
    {
        try {
            $this->screens = $this->getScreensFromDatabase();
        } catch (\Exception $e) {
            $this->screens = [];
        }

        $this->applyFilters();
    }

    private function getScreensFromDatabase()
    {
        $dbPath = $this->getSandboxDbPath();

        if (!File::exists($dbPath)) {
            return [];
        }

        try {
            $pdo = new \PDO("sqlite:$dbPath");
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // custom_screens 테이블이 없으면 생성
            $this->createScreensTableIfNotExists($pdo);

            $stmt = $pdo->query('SELECT * FROM custom_screens ORDER BY created_at DESC');
            $screens = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $screens;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function createScreensTableIfNotExists($pdo)
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS custom_screens (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                type TEXT DEFAULT 'dashboard',
                blade_template TEXT,
                livewire_component TEXT,
                connected_functions TEXT DEFAULT '[]',
                db_queries TEXT DEFAULT '[]',
                preview_data TEXT DEFAULT '[]',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ";

        $pdo->exec($sql);
    }

    public function selectScreen($id)
    {
        $screen = collect($this->screens)->firstWhere('id', $id);
        if ($screen) {
            $this->selectedScreen = $screen;
        }
    }

    public function togglePreview()
    {
        $this->previewMode = !$this->previewMode;
    }

    public function getPreviewUrl($screenId)
    {
        return route('sandbox.custom-screen-preview', ['id' => $screenId]);
    }

    public function openPreviewInNewWindow($screenId)
    {
        // JavaScript로 새 창을 열도록 이벤트 디스패치
        $url = $this->getPreviewUrl($screenId);
        $this->dispatch('openPreviewWindow', url: $url);
    }

    public function editScreen($id)
    {
        // 화면 생성기로 리다이렉트
        return redirect()->route('sandbox.custom-screen-creator', ['edit' => $id]);
    }

    public function duplicateScreen($id)
    {
        try {
            $screen = collect($this->screens)->firstWhere('id', $id);
            if (!$screen) {
                session()->flash('error', '화면을 찾을 수 없습니다.');
                return;
            }

            $dbPath = $this->getSandboxDbPath();
            if (File::exists($dbPath)) {
                $pdo = new \PDO("sqlite:$dbPath");
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                $stmt = $pdo->prepare('INSERT INTO custom_screens (title, description, type, blade_template, livewire_component, connected_functions, db_queries, preview_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([
                    $screen['title'] . ' (복사본)',
                    $screen['description'],
                    $screen['type'],
                    $screen['blade_template'],
                    $screen['livewire_component'],
                    $screen['connected_functions'],
                    $screen['db_queries'],
                    $screen['preview_data']
                ]);
            }

            $this->loadScreens();
            session()->flash('message', '화면이 복사되었습니다.');
        } catch (\Exception $e) {
            session()->flash('error', '화면 복사 중 오류가 발생했습니다.');
        }
    }

    public function deleteScreen($id)
    {
        try {
            $dbPath = $this->getSandboxDbPath();
            if (File::exists($dbPath)) {
                $pdo = new \PDO("sqlite:$dbPath");
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                $stmt = $pdo->prepare('DELETE FROM custom_screens WHERE id = ?');
                $stmt->execute([$id]);
            }

            if ($this->selectedScreen && $this->selectedScreen['id'] == $id) {
                $this->selectedScreen = null;
            }

            $this->loadScreens();
            session()->flash('message', '화면이 삭제되었습니다.');
        } catch (\Exception $e) {
            session()->flash('error', '화면 삭제 중 오류가 발생했습니다.');
        }
    }

    public function updatedSearch()
    {
        $this->applyFilters();
    }

    public function updatedFilterType()
    {
        $this->applyFilters();
    }

    private function applyFilters()
    {
        $allScreens = $this->screens;

        if (!empty($this->search)) {
            $allScreens = array_filter($allScreens, function($screen) {
                return str_contains(strtolower($screen['title']), strtolower($this->search)) ||
                       str_contains(strtolower($screen['description'] ?? ''), strtolower($this->search));
            });
        }

        if (!empty($this->filterType)) {
            $allScreens = array_filter($allScreens, function($screen) {
                return ($screen['type'] ?? 'dashboard') === $this->filterType;
            });
        }

        $this->screens = array_values($allScreens);
    }

    private function getSandboxDbPath()
    {
        return storage_path("sandbox/storage-sandbox-{$this->currentStorage}/database/sqlite.db");
    }
}
