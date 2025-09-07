<?php

namespace App\Livewire\Sandbox;

use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileManagerEditor extends Component
{
    public $selectedFileContent = '';
    public $selectedFileName = '';
    public $selectedFileExtension = '';
    public $selectedFileId = null;
    public $isFileSelected = false;
    
    protected $listeners = ['fileSelected' => 'loadFile'];
    
    public function loadFile($mediaId)
    {
        $media = Media::find($mediaId);
        
        if ($media && $this->isEditableFile($media)) {
            $this->selectedFileId = $mediaId;
            $this->selectedFileName = $media->name;
            $this->selectedFileExtension = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));
            $this->selectedFileContent = file_get_contents($media->getPath());
            $this->isFileSelected = true;
        } else {
            $this->resetEditor();
        }
    }
    
    public function saveFile()
    {
        if ($this->selectedFileId && $this->isFileSelected) {
            $media = Media::find($this->selectedFileId);
            if ($media) {
                file_put_contents($media->getPath(), $this->selectedFileContent);
                $this->dispatch('fileSaved', ['message' => '파일이 저장되었습니다.']);
            }
        }
    }
    
    public function resetEditor()
    {
        $this->selectedFileContent = '';
        $this->selectedFileName = '';
        $this->selectedFileExtension = '';
        $this->selectedFileId = null;
        $this->isFileSelected = false;
    }
    
    public function getEditorLanguage()
    {
        if (!$this->selectedFileExtension) {
            return 'plaintext';
        }
        
        $languageMap = [
            'php' => 'php',
            'js' => 'javascript',
            'jsx' => 'javascript',
            'ts' => 'typescript',
            'tsx' => 'typescript',
            'css' => 'css',
            'scss' => 'scss',
            'sass' => 'scss',
            'less' => 'less',
            'html' => 'html',
            'json' => 'json',
            'xml' => 'xml',
            'sql' => 'sql',
            'py' => 'python',
            'java' => 'java',
            'cpp' => 'cpp',
            'c' => 'c',
            'h' => 'c',
            'vue' => 'html',
            'md' => 'markdown',
            'yml' => 'yaml',
            'yaml' => 'yaml',
            'txt' => 'plaintext',
            'ini' => 'ini',
            'env' => 'plaintext'
        ];
        
        $extension = strtolower($this->selectedFileExtension);
        
        // blade.php 파일 처리
        if (str_ends_with(strtolower($this->selectedFileName), '.blade.php')) {
            return 'html';
        }
        
        return $languageMap[$extension] ?? 'plaintext';
    }
    
    private function isEditableFile($media)
    {
        $editableExtensions = [
            'php', 'js', 'css', 'html', 'blade.php', 'json', 'xml', 'txt', 'md',
            'sql', 'py', 'java', 'cpp', 'c', 'h', 'vue', 'jsx', 'tsx', 'ts',
            'scss', 'sass', 'less', 'yml', 'yaml', 'ini', 'env'
        ];
        
        $extension = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));
        
        return in_array($extension, $editableExtensions) || 
               str_ends_with(strtolower($media->name), '.blade.php');
    }
    
    public function render()
    {
        return view('livewire.sandbox.file-manager-editor');
    }
}
