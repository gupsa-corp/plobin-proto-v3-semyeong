<?php

namespace App\Livewire\Sandbox;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class FileManager extends Component
{
    public string $currentPath = 'files/views';
    public string $content = '';
    public string $fileName = '';
    public array $list = [];
    public bool $previewMode = false;

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

        if (!$disk->exists($sandboxPath)) {
            $disk->makeDirectory($sandboxPath);
        }

        $this->list = [
            'dirs' => $disk->directories($sandboxPath),
            'files' => $disk->files($sandboxPath)
        ];

        // 경로를 상대경로로 변환
        $this->list['dirs'] = array_map(fn($dir) => str_replace('ai-sandbox/', '', $dir), $this->list['dirs']);
        $this->list['files'] = array_map(fn($file) => str_replace('ai-sandbox/', '', $file), $this->list['files']);
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

        session()->flash('message', '파일이 저장되었습니다.');
        $this->refreshList();
    }

    public function deleteFile($file)
    {
        $sandboxFile = 'ai-sandbox/' . $file;
        $disk = $this->getDisk();

        if ($disk->exists($sandboxFile)) {
            $disk->delete($sandboxFile);
            session()->flash('message', '파일이 삭제되었습니다.');
            $this->refreshList();

            // 현재 편집중인 파일이면 초기화
            if ($this->fileName === basename($file)) {
                $this->content = '';
                $this->fileName = '';
            }
        }
    }

    public function renderBladePreview()
    {
        try {
            if (empty($this->content)) {
                return '<div class="text-gray-500 italic">내용이 없습니다.</div>';
            }

            // HTML 파일일 경우 직접 렌더링
            if (str_ends_with($this->fileName, '.html')) {
                return '<div class="border rounded bg-white p-4">' . $this->content . '</div>';
            }

            // Blade 템플릿일 경우 실제 렌더링 시도
            if (str_ends_with($this->fileName, '.blade.php')) {
                return $this->renderBladeTemplate();
            }

            // CSS 파일일 경우
            if (str_ends_with($this->fileName, '.css')) {
                return '<pre class="bg-gray-100 p-4 rounded overflow-auto text-sm"><code>' . htmlspecialchars($this->content) . '</code></pre>';
            }

            // JS 파일일 경우
            if (str_ends_with($this->fileName, '.js')) {
                return '<pre class="bg-gray-100 p-4 rounded overflow-auto text-sm"><code>' . htmlspecialchars($this->content) . '</code></pre>';
            }

            // 기타 파일
            return '<pre class="bg-gray-100 p-4 rounded overflow-auto text-sm"><code>' . htmlspecialchars($this->content) . '</code></pre>';
            
        } catch (\Exception $e) {
            return '<div class="text-red-500">미리보기 생성 중 오류: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }

    private function renderBladeTemplate()
    {
        try {
            // 임시 파일명 생성
            $tempName = 'sandbox_preview_' . time();
            
            // Blade 엔진을 사용해서 실제 렌더링
            $blade = app('view');
            
            // 임시로 뷰 컴포넌트에 내용 저장
            $tempContent = $this->content;
            
            // 간단한 변수 제공 (테스트용)
            $data = [
                'title' => '미리보기',
                'message' => 'Livewire 파일 매니저',
                'items' => ['item1', 'item2', 'item3'],
                'user' => (object)['name' => 'Test User', 'email' => 'test@example.com']
            ];

            // 임시 뷰 파일 생성
            $tempPath = resource_path('views/temp');
            if (!is_dir($tempPath)) {
                mkdir($tempPath, 0755, true);
            }
            
            $tempFile = $tempPath . '/' . $tempName . '.blade.php';
            file_put_contents($tempFile, $this->content);
            
            // Blade 템플릿 렌더링
            $rendered = $blade->make('temp.' . $tempName, $data)->render();
            
            // 임시 파일 삭제
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            
            return '<div class="border rounded bg-white p-4 rendered-preview">' . $rendered . '</div>';
            
        } catch (\Exception $e) {
            // 실패시 원본 내용 표시
            return '<div class="text-orange-500 mb-2">Blade 렌더링 실패: ' . htmlspecialchars($e->getMessage()) . '</div>' .
                   '<div class="border rounded bg-gray-50 p-4">' . 
                   '<pre class="text-sm"><code>' . htmlspecialchars($this->content) . '</code></pre>' . 
                   '</div>';
        }
    }

    public function render()
    {
        return view('700-page-sandbox.livewire.700-file-manager');
    }
}