<?php

namespace App\Livewire\SandboxManagement;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SandboxList extends Component
{
    use WithPagination;

    // 검색 및 필터 프로퍼티
    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    // 모달 및 상태 프로퍼티
    public $showCreateModal = false;
    public $showDeleteModal = false;
    public $editingName = null;
    
    // 폼 프로퍼티
    public $storageName = '';
    public $selectedTemplate = '';
    public $deleteTargetName = '';
    public $currentStorage = '';
    
    // 실시간 업데이트를 위한 프로퍼티
    protected $listeners = ['refreshSandboxes' => 'render'];

    public function mount()
    {
        $this->currentStorage = Session::get('sandbox_storage', '1');
    }

    public function render()
    {
        try {
            $sandboxes = $this->getSandboxes();
            $templates = $this->getTemplates();
            $statistics = $this->getStatistics();
            
            return view('livewire.sandbox-management.sandbox-list', [
                'sandboxes' => $sandboxes,
                'templates' => $templates,
                'statistics' => $statistics,
            ]);
        } catch (\Exception $e) {
            // For debugging, return a simple error view
            return view('livewire.sandbox-management.sandbox-list', [
                'sandboxes' => collect(),
                'templates' => [],
                'statistics' => [
                    'total' => 0,
                    'active' => 0,
                    'errors' => 0,
                    'templatesCount' => 0,
                    'totalSize' => 0
                ],
                'error' => $e->getMessage() . ' on line ' . $e->getLine()
            ]);
        }
    }

    protected function getSandboxes()
    {
        $storages = $this->getStorageList();
        
        // 검색 필터링
        if (!empty($this->search)) {
            $storages = array_filter($storages, function($storage) {
                return stripos($storage['name'], $this->search) !== false;
            });
        }
        
        // 정렬
        if (!empty($storages)) {
            usort($storages, function($a, $b) {
                $aValue = $a[$this->sortBy] ?? '';
                $bValue = $b[$this->sortBy] ?? '';
                
                if ($this->sortDirection === 'asc') {
                    return strcmp((string)$aValue, (string)$bValue);
                } else {
                    return strcmp((string)$bValue, (string)$aValue);
                }
            });
        }
        
        // 페이지네이션 시뮬레이션
        $currentPage = $this->getPage();
        $offset = ($currentPage - 1) * $this->perPage;
        $items = array_slice($storages, $offset, $this->perPage);
        
        // Collection으로 반환하여 Laravel pagination 형태로 만들기
        return new \Illuminate\Pagination\LengthAwarePaginator(
            collect($items),
            count($storages),
            $this->perPage,
            $currentPage,
            ['path' => request()->url()]
        );
    }

    protected function getTemplates()
    {
        $templates = [];
        $templateBasePath = storage_path('sandbox-template');

        if (!File::exists($templateBasePath)) {
            return $templates;
        }

        $directories = File::directories($templateBasePath);

        foreach ($directories as $directory) {
            $templateName = basename($directory);
            $templates[] = [
                'name' => $templateName,
                'display_name' => ucfirst($templateName),
                'path' => $directory,
                'file_count' => $this->getFileCount($directory),
                'size' => $this->getDirectorySize($directory),
            ];
        }

        usort($templates, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $templates;
    }

    protected function getStatistics()
    {
        $storages = $this->getStorageList();
        $templates = $this->getTemplates();
        
        $total = count($storages);
        $active = count(array_filter($storages, function($storage) {
            return $storage['is_current'] ?? false;
        }));
        $errors = count(array_filter($storages, function($storage) {
            return ($storage['status'] ?? 'active') === 'error';
        }));
        $templatesCount = count($templates);
        $totalSize = array_reduce($storages, function($carry, $storage) {
            return $carry + ($storage['size_bytes'] ?? 0);
        }, 0);

        return compact('total', 'active', 'errors', 'templatesCount', 'totalSize');
    }

    private function getStorageList()
    {
        $storages = [];
        $storagePath = storage_path('sandbox');

        if (!File::exists($storagePath)) {
            return $storages;
        }

        $directories = File::directories($storagePath);

        foreach ($directories as $directory) {
            $basename = basename($directory);
            
            if ($basename !== 'sandbox-template') {
                $storages[] = [
                    'name' => $basename,
                    'full_path' => $directory,
                    'created_at' => $this->getDirectoryCreatedAt($directory),
                    'size' => $this->getDirectorySize($directory),
                    'size_bytes' => $this->getDirectorySizeBytes($directory),
                    'file_count' => $this->getFileCount($directory),
                    'is_current' => $basename === $this->currentStorage,
                    'status' => $this->getStorageStatus($directory),
                ];
            }
        }

        return $storages;
    }

    // 검색 및 필터 메서드
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    // 샌드박스 생성
    public function openCreateModal()
    {
        $this->reset(['storageName', 'selectedTemplate']);
        $this->showCreateModal = true;
    }

    public function createSandbox()
    {
        $this->validate([
            'storageName' => 'required|string|regex:/^[a-zA-Z0-9_-]+$/|max:50',
            'selectedTemplate' => 'required|string|regex:/^[a-zA-Z0-9_-]+$/|max:50',
        ], [
            'storageName.required' => '스토리지 이름을 입력해주세요.',
            'storageName.regex' => '영문자, 숫자, 하이픈(-), 언더스코어(_)만 사용 가능합니다.',
            'storageName.max' => '스토리지 이름은 50자를 초과할 수 없습니다.',
            'selectedTemplate.required' => '템플릿을 선택해주세요.',
        ]);

        try {
            $targetPath = storage_path('sandbox') . '/' . $this->storageName;
            
            // 이미 존재하는지 확인
            if (File::exists($targetPath)) {
                $this->dispatch('sandbox-error', ['message' => '이미 존재하는 스토리지 이름입니다.']);
                return;
            }

            // 템플릿 경로 확인
            $templatePath = storage_path('sandbox-template') . '/' . $this->selectedTemplate;
            if (!File::exists($templatePath)) {
                $this->dispatch('sandbox-error', ['message' => '선택한 템플릿이 존재하지 않습니다.']);
                return;
            }

            // 템플릿 복사
            File::copyDirectory($templatePath, $targetPath);

            $this->showCreateModal = false;
            $this->dispatch('sandbox-created', ['message' => '샌드박스가 성공적으로 생성되었습니다.']);
            
        } catch (\Exception $e) {
            $this->dispatch('sandbox-error', ['message' => '샌드박스 생성 중 오류가 발생했습니다: ' . $e->getMessage()]);
        }
    }

    // 샌드박스 선택 (활성화)
    public function selectSandbox($storageName)
    {
        try {
            $storagePath = storage_path('sandbox') . '/' . $storageName;

            // 스토리지 존재 확인
            if (!File::exists($storagePath)) {
                $this->dispatch('sandbox-error', ['message' => '선택하려는 스토리지가 존재하지 않습니다.']);
                return;
            }

            // 데이터베이스 파일 존재 확인
            $dbPath = $storagePath . '/Backend/Databases/Release.sqlite';
            if (!File::exists($dbPath)) {
                $this->dispatch('sandbox-error', ['message' => '선택하려는 스토리지에 데이터베이스 파일이 없습니다.']);
                return;
            }

            // 세션에 저장
            Session::put('sandbox_storage', $storageName);
            $this->currentStorage = $storageName;

            $this->dispatch('sandbox-selected', ['message' => "스토리지 '{$storageName}'이 선택되었습니다."]);
            
        } catch (\Exception $e) {
            $this->dispatch('sandbox-error', ['message' => '스토리지 선택 중 오류가 발생했습니다.']);
        }
    }

    // 샌드박스 삭제
    public function confirmDelete($storageName)
    {
        $this->deleteTargetName = $storageName;
        $this->showDeleteModal = true;
    }

    public function deleteSandbox()
    {
        try {
            $storageName = $this->deleteTargetName;
            
            // template은 삭제할 수 없음
            if ($storageName === 'template') {
                $this->dispatch('sandbox-error', ['message' => '템플릿 스토리지는 삭제할 수 없습니다.']);
                return;
            }

            $storagePath = storage_path('sandbox') . '/' . $storageName;

            if (!File::exists($storagePath)) {
                $this->dispatch('sandbox-error', ['message' => '삭제하려는 스토리지가 존재하지 않습니다.']);
                return;
            }

            // 현재 선택된 스토리지인 경우 기본값으로 변경
            if (Session::get('sandbox_storage') === $storageName) {
                Session::put('sandbox_storage', '1');
                $this->currentStorage = '1';
            }

            File::deleteDirectory($storagePath);

            $this->showDeleteModal = false;
            $this->deleteTargetName = '';
            $this->dispatch('sandbox-deleted', ['message' => "스토리지 '{$storageName}'이 성공적으로 삭제되었습니다."]);
            
        } catch (\Exception $e) {
            $this->dispatch('sandbox-error', ['message' => '스토리지 삭제 중 오류가 발생했습니다: ' . $e->getMessage()]);
        }
    }

    public function refreshSandboxes()
    {
        // 컴포넌트 새로고침
        $this->currentStorage = Session::get('sandbox_storage', '1');
    }

    // 도우미 메서드들
    private function getDirectoryCreatedAt($path)
    {
        try {
            return Carbon::createFromTimestamp(filemtime($path))->format('Y-m-d H:i');
        } catch (\Exception $e) {
            return '알 수 없음';
        }
    }

    private function getDirectorySize($path)
    {
        try {
            $size = $this->getDirectorySizeBytes($path);
            return $this->formatBytes($size);
        } catch (\Exception $e) {
            return '알 수 없음';
        }
    }

    private function getDirectorySizeBytes($path)
    {
        $size = 0;

        if (is_dir($path)) {
            $files = File::allFiles($path);
            foreach ($files as $file) {
                $size += $file->getSize();
            }
        }

        return $size;
    }

    private function getFileCount($path)
    {
        try {
            return count(File::allFiles($path));
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getStorageStatus($path)
    {
        // 데이터베이스 파일 존재 여부로 상태 판단
        $dbPath = $path . '/Backend/Databases/Release.sqlite';
        if (File::exists($dbPath) && is_readable($dbPath)) {
            return 'active';
        } else {
            return 'error';
        }
    }

    private function formatBytes($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 1) . ' ' . $units[$unitIndex];
    }
}