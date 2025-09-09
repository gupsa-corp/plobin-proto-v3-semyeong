<?php

namespace App\Livewire\Sandbox\CustomScreens\Browser;

use Livewire\Component as LivewireComponent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Models\SandboxCustomScreen;

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
        try {
            $screens = SandboxCustomScreen::where('sandbox_type', $this->currentStorage)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($screen) {
                    return [
                        'id' => $screen->id,
                        'title' => $screen->title,
                        'description' => $screen->description,
                        'type' => $screen->type,
                        'folder_name' => $screen->folder_name,
                        'file_path' => $screen->file_path,
                        'created_at' => $screen->created_at->format('Y-m-d H:i:s'),
                        'file_exists' => $screen->fileExists(),
                        'full_path' => $screen->getFullFilePath(),
                        'file_size' => $screen->getFileSize(),
                        'file_modified' => $screen->getFileModified(),
                    ];
                })
                ->toArray();

            return $screens;
        } catch (\Exception $e) {
            return [];
        }
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
            $originalScreen = SandboxCustomScreen::find($id);
            if (!$originalScreen) {
                session()->flash('error', '화면을 찾을 수 없습니다.');
                return;
            }

            // 새로운 폴더명 생성
            $newFolderName = $originalScreen->folder_name . '-copy-' . time();
            $newFileName = 'custom-screens/' . $newFolderName . '/000-content.blade.php';
            
            $sourcePath = $originalScreen->getFullFilePath();
            $targetPath = storage_path("sandbox/storage-sandbox-{$this->currentStorage}/" . $newFileName);
            
            // 새 폴더 생성
            $targetDir = dirname($targetPath);
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            
            // 파일 복사
            if (File::exists($sourcePath)) {
                File::copy($sourcePath, $targetPath);
            }

            // DB에 메타데이터 추가
            SandboxCustomScreen::create([
                'title' => $originalScreen->title . ' (복사본)',
                'description' => $originalScreen->description,
                'type' => $originalScreen->type,
                'folder_name' => $newFolderName,
                'file_path' => $newFileName,
                'sandbox_type' => $originalScreen->sandbox_type,
            ]);

            $this->loadScreens();
            session()->flash('message', '화면이 복사되었습니다.');
        } catch (\Exception $e) {
            session()->flash('error', '화면 복사 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    public function deleteScreen($id)
    {
        try {
            $screen = SandboxCustomScreen::find($id);
            if (!$screen) {
                session()->flash('error', '화면을 찾을 수 없습니다.');
                return;
            }

            // 파일 및 폴더 삭제
            $filePath = $screen->getFullFilePath();
            $folderPath = dirname($filePath);
            
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            
            if (File::exists($folderPath) && File::isDirectory($folderPath)) {
                File::deleteDirectory($folderPath);
            }

            // DB에서 메타데이터 삭제
            $screen->delete();

            if ($this->selectedScreen && $this->selectedScreen['id'] == $id) {
                $this->selectedScreen = null;
            }

            $this->loadScreens();
            session()->flash('message', '화면이 삭제되었습니다.');
        } catch (\Exception $e) {
            session()->flash('error', '화면 삭제 중 오류가 발생했습니다: ' . $e->getMessage());
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

}
