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

    public function render()
    {
        return view('livewire.sandbox.file-manager');
    }
}