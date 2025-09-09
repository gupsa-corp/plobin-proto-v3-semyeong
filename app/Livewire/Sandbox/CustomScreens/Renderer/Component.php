<?php

namespace App\Livewire\Sandbox\CustomScreens\Renderer;

use Livewire\Component as LivewireComponent;
use Illuminate\Support\Facades\File;

class Component extends LivewireComponent
{
    public $screen;
    public $renderedContent = '';
    public $error = null;

    public function mount($screenData)
    {
        $this->screen = $screenData;
        $this->renderScreen();
    }

    public function render()
    {
        return view('livewire.sandbox.custom-screens.renderer-component');
    }

    public function renderScreen()
    {
        try {
            if (!$this->screen || empty($this->screen['blade_template'])) {
                $this->error = '렌더링할 화면 데이터가 없습니다.';
                return;
            }

            // 블레이드 템플릿 렌더링
            $this->renderedContent = $this->renderBladeTemplate($this->screen['blade_template']);
            $this->error = null;
        } catch (\Exception $e) {
            $this->error = '렌더링 오류: ' . $e->getMessage();
            $this->renderedContent = '';
        }
    }

    private function renderBladeTemplate($template)
    {
        // 향상된 블레이드 템플릿 시뮬레이션
        // 더 많은 샘플 데이터와 복잡한 렌더링 지원
        
        $sampleData = [
            'title' => $this->screen['title'] ?? '샘플 화면',
            'description' => $this->screen['description'] ?? '이것은 미리보기입니다',
            'users' => [
                ['id' => 1, 'name' => '홍길동', 'email' => 'hong@example.com', 'status' => 'active'],
                ['id' => 2, 'name' => '김철수', 'email' => 'kim@example.com', 'status' => 'inactive'],
                ['id' => 3, 'name' => '이영희', 'email' => 'lee@example.com', 'status' => 'active']
            ],
            'projects' => [
                ['id' => 1, 'name' => '프로젝트 A', 'status' => 'active', 'progress' => 75],
                ['id' => 2, 'name' => '프로젝트 B', 'status' => 'pending', 'progress' => 30],
                ['id' => 3, 'name' => '프로젝트 C', 'status' => 'completed', 'progress' => 100]
            ],
            'stats' => [
                'total_users' => 156,
                'active_users' => 142,
                'total_projects' => 23,
                'completed_projects' => 18
            ]
        ];

        $rendered = $template;
        
        // 기본 변수 치환 - 단순 변수들
        $rendered = preg_replace_callback('/\{\{\s*\$(\w+)\s*\}\}/', function($matches) use ($sampleData) {
            $varName = $matches[1];
            return $sampleData[$varName] ?? "[{$varName}]";
        }, $rendered);
        
        // 배열 변수 치환 (예: {{ $project["name"] }})
        $rendered = preg_replace_callback('/\{\{\s*\$(\w+)\[\s*["\'](\w+)["\']\s*\]\s*\}\}/', function($matches) use ($sampleData) {
            $arrayName = $matches[1];
            $key = $matches[2];
            
            // 현재 컨텍스트에서 변수 찾기 (foreach 내부에서 사용될 수 있음)
            if ($arrayName === 'user' || $arrayName === 'project') {
                return "[{$arrayName}.{$key}]"; // foreach에서 처리됨
            }
            
            return isset($sampleData[$arrayName][$key]) ? $sampleData[$arrayName][$key] : "[{$arrayName}.{$key}]";
        }, $rendered);
        
        // @if 처리 개선
        $rendered = preg_replace_callback('/@if\s*\(\s*\$(\w+)\s*\)(.*?)(?:@else(.*?))?@endif/s', function($matches) use ($sampleData) {
            $condition = $matches[1];
            $ifContent = $matches[2];
            $elseContent = isset($matches[3]) ? $matches[3] : '';
            
            $conditionMet = false;
            if (isset($sampleData[$condition])) {
                $conditionMet = !empty($sampleData[$condition]);
            }
            
            return $conditionMet ? $ifContent : $elseContent;
        }, $rendered);
        
        // @foreach 처리 개선 - users
        $rendered = preg_replace_callback('/@foreach\s*\(\s*\$users\s+as\s+\$user\s*\)(.*?)@endforeach/s', function($matches) use ($sampleData) {
            $foreachContent = '';
            $itemTemplate = $matches[1];
            
            foreach ($sampleData['users'] as $user) {
                $itemContent = $itemTemplate;
                $itemContent = str_replace('{{ $user[\'name\'] }}', $user['name'], $itemContent);
                $itemContent = str_replace('{{ $user[\'email\'] }}', $user['email'], $itemContent);
                $itemContent = str_replace('{{ $user[\'status\'] }}', $user['status'], $itemContent);
                $itemContent = str_replace('{{ $user[\'id\'] }}', $user['id'], $itemContent);
                
                // 따옴표 스타일 다양성 지원
                $itemContent = str_replace('{{ $user["name"] }}', $user['name'], $itemContent);
                $itemContent = str_replace('{{ $user["email"] }}', $user['email'], $itemContent);
                $itemContent = str_replace('{{ $user["status"] }}', $user['status'], $itemContent);
                $itemContent = str_replace('{{ $user["id"] }}', $user['id'], $itemContent);
                
                $foreachContent .= $itemContent;
            }
            
            return $foreachContent;
        }, $rendered);
        
        // @foreach 처리 개선 - projects
        $rendered = preg_replace_callback('/@foreach\s*\(\s*\$projects\s+as\s+\$project\s*\)(.*?)@endforeach/s', function($matches) use ($sampleData) {
            $foreachContent = '';
            $itemTemplate = $matches[1];
            
            foreach ($sampleData['projects'] as $project) {
                $itemContent = $itemTemplate;
                $itemContent = str_replace('{{ $project[\'name\'] }}', $project['name'], $itemContent);
                $itemContent = str_replace('{{ $project[\'status\'] }}', $project['status'], $itemContent);
                $itemContent = str_replace('{{ $project[\'progress\'] }}', $project['progress'], $itemContent);
                $itemContent = str_replace('{{ $project[\'id\'] }}', $project['id'], $itemContent);
                
                // 따옴표 스타일 다양성 지원
                $itemContent = str_replace('{{ $project["name"] }}', $project['name'], $itemContent);
                $itemContent = str_replace('{{ $project["status"] }}', $project['status'], $itemContent);
                $itemContent = str_replace('{{ $project["progress"] }}', $project['progress'], $itemContent);
                $itemContent = str_replace('{{ $project["id"] }}', $project['id'], $itemContent);
                
                $foreachContent .= $itemContent;
            }
            
            return $foreachContent;
        }, $rendered);

        return $rendered;
    }

    public function refreshPreview()
    {
        $this->renderScreen();
        $this->dispatch('preview-updated');
    }
}