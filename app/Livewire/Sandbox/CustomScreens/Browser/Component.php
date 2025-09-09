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
            // 데이터베이스에서 커스텀 화면 가져오기
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
                        'is_template' => false,
                    ];
                })
                ->toArray();

            // template 스토리지의 frontend 폴더에서 템플릿 화면들 가져오기 (아직 동기화되지 않은 것만)
            $templatePath = storage_path('sandbox-template/storage-sandbox-template/frontend');
            if (File::exists($templatePath)) {
                $templateScreens = [];
                $folders = File::directories($templatePath);
                
                // 이미 동기화된 화면들의 folder_name 목록
                $syncedFolderNames = collect($screens)->pluck('folder_name')->toArray();
                
                foreach ($folders as $folder) {
                    $folderName = basename($folder);
                    $contentFile = $folder . '/000-content.blade.php';
                    
                    // 이미 동기화된 템플릿이면 건너뛰기
                    if (in_array($folderName, $syncedFolderNames)) {
                        continue;
                    }
                    
                    if (File::exists($contentFile)) {
                        // 폴더명에서 화면 정보 추출
                        $parts = explode('-', $folderName, 3);
                        $screenId = $parts[0] ?? '000';
                        $screenType = $parts[1] ?? 'screen';
                        $screenName = $parts[2] ?? 'unnamed';
                        
                        $templateScreens[] = [
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
                        ];
                    }
                }
                
                // 템플릿 화면들을 기존 화면 목록에 추가 (템플릿이 먼저 오도록)
                $screens = array_merge($templateScreens, $screens);
            }

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

    public function copyTemplateToCustomScreen($templateId)
    {
        try {
            // 템플릿 화면 정보 찾기
            $template = collect($this->screens)->firstWhere('id', $templateId);
            if (!$template || !$template['is_template']) {
                session()->flash('error', '템플릿을 찾을 수 없습니다.');
                return;
            }

            // frontend/ 경로에 직접 저장 (동일한 폴더명 사용)
            $folderName = $template['folder_name'];
            $fileName = 'frontend/' . $folderName . '/000-content.blade.php';
            
            // 현재 스토리지의 frontend 경로에 저장
            $targetPath = storage_path("sandbox/storage-sandbox-{$this->currentStorage}/" . $fileName);
            
            // 새 폴더 생성
            $targetDir = dirname($targetPath);
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            
            // 템플릿 파일 복사
            if (File::exists($template['full_path'])) {
                File::copy($template['full_path'], $targetPath);
            } else {
                session()->flash('error', '템플릿 파일을 찾을 수 없습니다.');
                return;
            }

            // 기존에 같은 제목의 화면이 있는지 확인하고 업데이트 또는 생성
            $existingScreen = SandboxCustomScreen::where('sandbox_type', $this->currentStorage)
                ->where('folder_name', $folderName)
                ->first();

            if ($existingScreen) {
                // 기존 화면 업데이트
                $existingScreen->update([
                    'title' => $template['title'],
                    'description' => $template['description'],
                    'type' => $template['type'],
                    'updated_at' => now(),
                ]);
                session()->flash('message', "'{$template['title']}' 화면이 업데이트되었습니다.");
            } else {
                // 새 화면 생성
                SandboxCustomScreen::create([
                    'title' => $template['title'],
                    'description' => $template['description'],
                    'type' => $template['type'],
                    'folder_name' => $folderName,
                    'file_path' => $fileName,
                    'sandbox_type' => $this->currentStorage,
                ]);
                session()->flash('message', "'{$template['title']}' 화면이 동기화되었습니다.");
            }

            $this->loadScreens();
        } catch (\Exception $e) {
            session()->flash('error', '템플릿 동기화 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    public function syncAllTemplates()
    {
        try {
            $templateScreens = collect($this->screens)->where('is_template', true);
            $syncedCount = 0;
            $updatedCount = 0;
            
            foreach ($templateScreens as $template) {
                // frontend/ 경로에 직접 저장
                $folderName = $template['folder_name'];
                $fileName = 'frontend/' . $folderName . '/000-content.blade.php';
                $targetPath = storage_path("sandbox/storage-sandbox-{$this->currentStorage}/" . $fileName);
                
                // 폴더 생성
                $targetDir = dirname($targetPath);
                if (!File::exists($targetDir)) {
                    File::makeDirectory($targetDir, 0755, true);
                }
                
                // 파일 복사
                if (File::exists($template['full_path'])) {
                    File::copy($template['full_path'], $targetPath);
                    
                    // DB 업데이트 또는 생성
                    $existingScreen = SandboxCustomScreen::where('sandbox_type', $this->currentStorage)
                        ->where('folder_name', $folderName)
                        ->first();

                    if ($existingScreen) {
                        $existingScreen->update([
                            'title' => $template['title'],
                            'description' => $template['description'],
                            'type' => $template['type'],
                            'updated_at' => now(),
                        ]);
                        $updatedCount++;
                    } else {
                        SandboxCustomScreen::create([
                            'title' => $template['title'],
                            'description' => $template['description'],
                            'type' => $template['type'],
                            'folder_name' => $folderName,
                            'file_path' => $fileName,
                            'sandbox_type' => $this->currentStorage,
                        ]);
                        $syncedCount++;
                    }
                }
            }

            $this->loadScreens();
            
            $message = [];
            if ($syncedCount > 0) $message[] = "{$syncedCount}개 화면 새로 동기화";
            if ($updatedCount > 0) $message[] = "{$updatedCount}개 화면 업데이트";
            
            if (!empty($message)) {
                session()->flash('message', '템플릿 일괄 동기화 완료: ' . implode(', ', $message));
            } else {
                session()->flash('message', '모든 템플릿이 이미 최신 상태입니다.');
            }
            
        } catch (\Exception $e) {
            session()->flash('error', '일괄 동기화 중 오류가 발생했습니다: ' . $e->getMessage());
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
