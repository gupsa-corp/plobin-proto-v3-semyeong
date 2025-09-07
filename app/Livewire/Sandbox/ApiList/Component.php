<?php

namespace App\Livewire\Sandbox\ApiList;

use Livewire\Component as LivewireComponent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class Component extends LivewireComponent
{
    public $apis = [];
    public $selectedApi = null;
    public $showPreview = false;
    public $previewContent = '';

    public function mount()
    {
        $this->loadApis();
    }

    public function render()
    {
        return view('700-page-sandbox.704-livewire-api-list');
    }

    public function loadApis()
    {
        $apiPath = storage_path('sandbox/api');
        
        if (!File::exists($apiPath)) {
            $this->apis = [];
            return;
        }

        $this->apis = [];
        $files = File::files($apiPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                $apiInfo = $this->parseApiInfo($content);
                
                $this->apis[] = [
                    'filename' => $file->getFilename(),
                    'name' => $apiInfo['name'] ?? pathinfo($file->getFilename(), PATHINFO_FILENAME),
                    'description' => $apiInfo['description'] ?? '',
                    'created' => $apiInfo['created'] ?? 'Unknown',
                    'size' => $file->getSize(),
                    'path' => $file->getPathname(),
                    'methods' => $this->extractMethods($content),
                    'routes' => $this->extractRoutes($content)
                ];
            }
        }

        // 생성일자 기준 내림차순 정렬
        usort($this->apis, function($a, $b) {
            return strtotime($b['created']) - strtotime($a['created']);
        });
    }

    private function parseApiInfo($content)
    {
        $info = [];
        
        // API 메타 정보 추출
        if (preg_match('/\* API Name: (.+)/', $content, $matches)) {
            $info['name'] = trim($matches[1]);
        }
        
        if (preg_match('/\* Description: (.+)/', $content, $matches)) {
            $info['description'] = trim($matches[1]);
        }
        
        if (preg_match('/\* Created: (.+)/', $content, $matches)) {
            $info['created'] = trim($matches[1]);
        }
        
        return $info;
    }

    private function extractMethods($content)
    {
        $methods = [];
        
        // public function 패턴 찾기
        if (preg_match_all('/public function (\w+)\([^)]*\)/', $content, $matches)) {
            foreach ($matches[1] as $method) {
                if (!in_array($method, ['__construct', 'render', 'mount'])) {
                    $methods[] = $method;
                }
            }
        }
        
        return $methods;
    }

    private function extractRoutes($content)
    {
        $routes = [];
        
        // 일반적인 REST API 메서드들에 대한 라우트 추정
        if (strpos($content, 'function index(') !== false) {
            $routes[] = 'GET /api/endpoint';
        }
        if (strpos($content, 'function store(') !== false) {
            $routes[] = 'POST /api/endpoint';
        }
        if (strpos($content, 'function show(') !== false) {
            $routes[] = 'GET /api/endpoint/{id}';
        }
        if (strpos($content, 'function update(') !== false) {
            $routes[] = 'PUT /api/endpoint/{id}';
        }
        if (strpos($content, 'function destroy(') !== false) {
            $routes[] = 'DELETE /api/endpoint/{id}';
        }
        
        return $routes;
    }

    public function viewApi($filename)
    {
        $api = collect($this->apis)->firstWhere('filename', $filename);
        
        if ($api) {
            $this->selectedApi = $api;
            $this->previewContent = File::get($api['path']);
            $this->showPreview = true;
        }
    }

    public function closePreview()
    {
        $this->showPreview = false;
        $this->selectedApi = null;
        $this->previewContent = '';
    }

    public function deleteApi($filename)
    {
        $apiPath = storage_path('sandbox/api/' . $filename);
        
        if (File::exists($apiPath)) {
            File::delete($apiPath);
            session()->flash('message', 'API 파일이 삭제되었습니다.');
            $this->loadApis();
        } else {
            session()->flash('error', 'API 파일을 찾을 수 없습니다.');
        }
    }

    public function copyApi($filename)
    {
        $api = collect($this->apis)->firstWhere('filename', $filename);
        
        if ($api) {
            $originalPath = $api['path'];
            $originalContent = File::get($originalPath);
            
            // 새 파일명 생성
            $pathInfo = pathinfo($filename);
            $copyFilename = $pathInfo['filename'] . '_copy_' . time() . '.' . $pathInfo['extension'];
            $copyPath = storage_path('sandbox/api/' . $copyFilename);
            
            // 메타 정보 업데이트
            $updatedContent = preg_replace(
                '/\* API Name: (.+)/',
                '* API Name: ' . $pathInfo['filename'] . ' Copy',
                $originalContent
            );
            
            $updatedContent = preg_replace(
                '/\* Created: (.+)/',
                '* Created: ' . now()->format('Y-m-d H:i:s'),
                $updatedContent
            );
            
            File::put($copyPath, $updatedContent);
            session()->flash('message', 'API 파일이 복사되었습니다.');
            $this->loadApis();
        }
    }

    public function downloadApi($filename)
    {
        $apiPath = storage_path('sandbox/api/' . $filename);
        
        if (File::exists($apiPath)) {
            return response()->download($apiPath);
        }
        
        session()->flash('error', 'API 파일을 찾을 수 없습니다.');
    }

    public function formatFileSize($bytes)
    {
        if ($bytes >= 1024 * 1024) {
            return round($bytes / (1024 * 1024), 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function refreshList()
    {
        $this->loadApis();
        session()->flash('message', 'API 목록이 새로고침되었습니다.');
    }
}