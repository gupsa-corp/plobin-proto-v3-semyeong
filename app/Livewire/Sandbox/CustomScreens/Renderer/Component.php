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
        // 간단한 블레이드 템플릿 시뮬레이션
        // 실제로는 더 복잡한 렌더링이 필요하지만, 미리보기용으로 기본 변수 치환
        
        $sampleData = [
            'title' => $this->screen['title'] ?? '제목 없음',
            'users' => [
                ['name' => '홍길동', 'email' => 'hong@example.com'],
                ['name' => '김철수', 'email' => 'kim@example.com'],
                ['name' => '이영희', 'email' => 'lee@example.com']
            ]
        ];

        $rendered = $template;
        
        // 기본 변수 치환
        $rendered = str_replace('{{ $title }}', $sampleData['title'], $rendered);
        
        // @if 처리 (단순화)
        if (preg_match('/@if\(\$users\)(.*?)@else(.*?)@endif/s', $rendered, $matches)) {
            if (!empty($sampleData['users'])) {
                $rendered = str_replace($matches[0], $matches[1], $rendered);
            } else {
                $rendered = str_replace($matches[0], $matches[2], $rendered);
            }
        }
        
        // @foreach 처리 (단순화)
        if (preg_match('/@foreach\(\$users as \$user\)(.*?)@endforeach/s', $rendered, $matches)) {
            $foreachContent = '';
            foreach ($sampleData['users'] as $user) {
                $itemContent = $matches[1];
                $itemContent = str_replace('{{ $user[\'name\'] }}', $user['name'], $itemContent);
                $itemContent = str_replace('{{ $user[\'email\'] }}', $user['email'], $itemContent);
                $foreachContent .= $itemContent;
            }
            $rendered = str_replace($matches[0], $foreachContent, $rendered);
        }

        return $rendered;
    }

    public function refreshPreview()
    {
        $this->renderScreen();
        $this->dispatch('preview-updated');
    }
}