<?php

namespace App\Livewire\Sandbox\CustomScreens\Browser;

use Livewire\Component as LivewireComponent;
use Illuminate\Support\Facades\File;

class Component extends LivewireComponent
{
    public $screens = [];
    public $search = '';
    public $filterType = '';
    public $selectedScreen = null;
    public $previewMode = false;

    public function mount()
    {
        $this->loadScreens();
    }

    public function render()
    {
        return view('livewire.sandbox.custom-screens.browser-component');
    }

    public function loadScreens()
    {
        try {
            $this->screens = $this->getScreensFromTemplate();
        } catch (\Exception $e) {
            $this->screens = [];
        }

        $this->applyFilters();
    }

    private function getScreensFromTemplate()
    {
        $screens = [];
        $templatePath = storage_path('sandbox/storage-sandbox-template/frontend');

        if (File::exists($templatePath)) {
            $folders = File::directories($templatePath);

            foreach ($folders as $folder) {
                $folderName = basename($folder);
                $contentFile = $folder . '/000-content.blade.php';

                if (File::exists($contentFile)) {
                    // 폴더명에서 화면 정보 추출
                    $parts = explode('-', $folderName, 3);
                    $screenId = $parts[0] ?? '000';
                    $screenType = $parts[1] ?? 'screen';
                    $screenName = $parts[2] ?? 'unnamed';

                    // 파일 내용 읽기
                    $fileContent = File::get($contentFile);

                    $screens[] = [
                        'id' => 'template_' . $screenId,
                        'title' => str_replace('-', ' ', $screenName),
                        'description' => '템플릿 화면 - ' . str_replace('-', ' ', $screenName),
                        'type' => $screenType,
                        'folder_name' => $folderName,
                        'file_path' => 'frontend/' . $folderName . '/000-content.blade.php',
                        'created_at' => date('Y-m-d H:i:s', File::lastModified($contentFile)),
                        'file_exists' => true,
                        'full_path' => $contentFile,
                        'file_size' => File::size($contentFile),
                        'file_modified' => date('Y-m-d H:i:s', File::lastModified($contentFile)),
                        'is_template' => true,
                        'blade_template' => $fileContent, // 렌더러가 기대하는 템플릿 데이터 추가
                    ];
                }
            }
        }

        // 생성 날짜 기준 내림차순 정렬
        usort($screens, function($a, $b) {
            return strcmp($b['created_at'], $a['created_at']);
        });

        return $screens;
    }

    public function selectScreen($id)
    {
        $screen = collect($this->screens)->firstWhere('id', $id);
        if ($screen) {
            $this->selectedScreen = $screen;
            $this->previewMode = true; // 화면 선택시 자동으로 미리보기 모드 활성화
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
        // 화면 생성기로 리다이렉트 (템플릿 편집 모드)
        return redirect()->route('sandbox.custom-screen-creator', ['template' => $id]);
    }

    public function duplicateScreen($id)
    {
        try {
            $originalScreen = collect($this->screens)->firstWhere('id', $id);
            if (!$originalScreen) {
                session()->flash('error', '화면을 찾을 수 없습니다.');
                return;
            }

            // 새로운 폴더명 생성 (템플릿 내에서)
            $templatePath = storage_path('sandbox/storage-sandbox-template/frontend');
            $newFolderName = $this->generateNewFolderName($templatePath, $originalScreen['folder_name']);

            $sourcePath = $originalScreen['full_path'];
            $targetPath = $templatePath . '/' . $newFolderName . '/000-content.blade.php';

            // 새 폴더 생성
            $targetDir = dirname($targetPath);
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            // 파일 복사
            if (File::exists($sourcePath)) {
                File::copy($sourcePath, $targetPath);
            }

            $this->loadScreens();
            session()->flash('message', '화면이 복사되었습니다: ' . $newFolderName);
        } catch (\Exception $e) {
            session()->flash('error', '화면 복사 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    private function generateNewFolderName($basePath, $originalName)
    {
        $counter = 1;
        do {
            $newName = $originalName . '-copy-' . $counter;
            $counter++;
        } while (File::exists($basePath . '/' . $newName));

        return $newName;
    }

    public function deleteScreen($id)
    {
        try {
            $screen = collect($this->screens)->firstWhere('id', $id);
            if (!$screen) {
                session()->flash('error', '화면을 찾을 수 없습니다.');
                return;
            }

            // 파일 및 폴더 삭제 (템플릿 내에서만)
            $filePath = $screen['full_path'];
            $folderPath = dirname($filePath);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            if (File::exists($folderPath) && File::isDirectory($folderPath)) {
                File::deleteDirectory($folderPath);
            }

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
