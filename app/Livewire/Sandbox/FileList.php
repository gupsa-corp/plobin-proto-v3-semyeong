<?php

namespace App\Livewire\Sandbox;

use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class FileList extends Component
{
    public $currentPath = '';
    public $currentStorage;
    public $items = [];
    public $parentPath = '';
    public $breadcrumbs = [];
    
    public function mount()
    {
        $this->currentStorage = Session::get('sandbox_storage', '1');
        $this->loadFileList();
    }
    
    public function navigateTo($path)
    {
        $this->currentPath = $path;
        $this->loadFileList();
    }
    
    public function goToParent()
    {
        if ($this->parentPath !== null) {
            $this->currentPath = $this->parentPath;
            $this->loadFileList();
        }
    }
    
    public function goToRoot()
    {
        $this->currentPath = '';
        $this->loadFileList();
    }
    
    public function refreshList()
    {
        $this->currentStorage = Session::get('sandbox_storage', '1');
        $this->loadFileList();
    }
    
    private function loadFileList()
    {
        $storagePath = storage_path('storage-sandbox-' . $this->currentStorage);
        $targetPath = $storagePath . '/' . ltrim($this->currentPath, '/');
        
        // 보안 체크: 샌드박스 디렉토리를 벗어나는 경로 차단
        $realTargetPath = realpath($targetPath);
        $realStoragePath = realpath($storagePath);
        
        if (!$realTargetPath || !str_starts_with($realTargetPath, $realStoragePath)) {
            $this->items = [];
            return;
        }
        
        if (!File::exists($targetPath) || !File::isDirectory($targetPath)) {
            $this->items = [];
            return;
        }
        
        $files = [];
        $directories = [];
        
        try {
            $items = File::glob($targetPath . '/*');
            
            foreach ($items as $item) {
                $relativePath = str_replace($storagePath . '/', '', $item);
                $basename = basename($item);
                $isDirectory = File::isDirectory($item);
                
                $itemData = [
                    'name' => $basename,
                    'path' => $relativePath,
                    'is_directory' => $isDirectory,
                    'size' => $isDirectory ? null : $this->formatBytes(File::size($item)),
                    'modified_at' => date('Y-m-d H:i:s', File::lastModified($item)),
                    'extension' => $isDirectory ? null : File::extension($item),
                ];
                
                if ($isDirectory) {
                    $directories[] = $itemData;
                } else {
                    $files[] = $itemData;
                }
            }
            
            // 디렉토리를 먼저, 파일을 나중에 정렬
            usort($directories, fn($a, $b) => strcasecmp($a['name'], $b['name']));
            usort($files, fn($a, $b) => strcasecmp($a['name'], $b['name']));
            
            $this->items = array_merge($directories, $files);
            $this->parentPath = dirname($this->currentPath) === '.' ? '' : dirname($this->currentPath);
            $this->generateBreadcrumbs();
            
        } catch (\Exception $e) {
            $this->items = [];
        }
    }
    
    private function generateBreadcrumbs()
    {
        $this->breadcrumbs = [];
        
        if (empty($this->currentPath)) {
            return;
        }
        
        $parts = explode('/', trim($this->currentPath, '/'));
        $path = '';
        
        foreach ($parts as $part) {
            $path = trim($path . '/' . $part, '/');
            $this->breadcrumbs[] = [
                'name' => $part,
                'path' => $path,
            ];
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
    
    private function getFileIcon($extension, $isDirectory)
    {
        if ($isDirectory) {
            return '📁';
        }
        
        return match($extension) {
            'php' => '🐘',
            'js' => '📜',
            'html', 'htm' => '🌐',
            'css' => '🎨',
            'txt' => '📄',
            'md' => '📝',
            'json' => '🔧',
            'xml' => '📋',
            'sql' => '🗃️',
            'jpg', 'jpeg', 'png', 'gif' => '🖼️',
            default => '📄',
        };
    }
    
    public function render()
    {
        return view('700-page-sandbox.703-page-file-list.700-livewire-file-list', [
            'items' => $this->items,
            'currentPath' => $this->currentPath,
            'currentStorage' => $this->currentStorage,
            'parentPath' => $this->parentPath,
            'breadcrumbs' => $this->breadcrumbs,
            'getFileIcon' => fn($ext, $isDir) => $this->getFileIcon($ext, $isDir),
        ]);
    }
}