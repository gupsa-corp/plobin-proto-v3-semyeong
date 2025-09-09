<?php

namespace App\Livewire\Sandbox\BladeList;

use Livewire\Component as LivewireComponent;

class Component extends LivewireComponent
{
    public $blades = [];
    public $search = '';
    public $filterType = '';
    public $previewBlade = null;
    public $previewContent = '';

    public function mount()
    {
        $this->loadBlades();
    }

    public function render()
    {
        return view('700-page-sandbox.706-livewire-blade-list');
    }

    public function loadBlades()
    {
        // 간단한 샘플 데이터로 시작
        $this->blades = [
            [
                'id' => 1,
                'title' => '사용자 프로필 컴포넌트',
                'description' => '사용자 정보를 표시하는 재사용 가능한 컴포넌트',
                'type' => 'component',
                'size' => 2048,
                'created_at' => '2024-01-15 10:30:00',
                'is_file' => false
            ],
            [
                'id' => 2,
                'title' => '메인 레이아웃',
                'description' => '사이트의 기본 레이아웃 템플릿',
                'type' => 'layout',
                'size' => 5120,
                'created_at' => '2024-01-10 14:20:00',
                'is_file' => false
            ],
            [
                'id' => 3,
                'title' => '연락처 폼',
                'description' => '사용자 문의를 위한 폼 템플릿',
                'type' => 'form',
                'size' => 1536,
                'created_at' => '2024-01-20 09:15:00',
                'is_file' => false
            ]
        ];

        $this->applyFilters();
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
        $allBlades = $this->blades;

        if (!empty($this->search)) {
            $allBlades = array_filter($allBlades, function($blade) {
                return str_contains(strtolower($blade['title']), strtolower($this->search)) ||
                       str_contains(strtolower($blade['description']), strtolower($this->search));
            });
        }

        if (!empty($this->filterType)) {
            $allBlades = array_filter($allBlades, function($blade) {
                return ($blade['type'] ?? 'basic') === $this->filterType;
            });
        }

        $this->blades = array_values($allBlades);
    }

    public function deleteBlade($id)
    {
        // 간단한 삭제 로직
        $this->blades = array_filter($this->blades, function($blade) use ($id) {
            return $blade['id'] != $id;
        });
        session()->flash('message', 'Blade 템플릿이 삭제되었습니다.');
    }

    public function previewBlade($id)
    {
        $blade = collect($this->blades)->firstWhere('id', $id);
        if ($blade) {
            $this->previewBlade = $blade;
            $this->previewContent = $this->getSampleBladeContent($blade);
        }
    }

    public function editBlade($id)
    {
        // 간단한 알림
        session()->flash('message', '편집 기능은 곧 추가될 예정입니다.');
    }

    private function getSampleBladeContent($blade)
    {
        $type = $blade['type'] ?? 'basic';

        switch ($type) {
            case 'component':
                return '<div class="user-profile">' . PHP_EOL .
                       '    <h3>{{ $user->name }}</h3>' . PHP_EOL .
                       '    <p>{{ $user->email }}</p>' . PHP_EOL .
                       '</div>';
            case 'layout':
                return '<!DOCTYPE html>' . PHP_EOL .
                       '<html>' . PHP_EOL .
                       '<head><title>{{ $title ?? \'Default\' }}</title></head>' . PHP_EOL .
                       '<body>' . PHP_EOL .
                       '    <main>{{ $slot }}</main>' . PHP_EOL .
                       '</body>' . PHP_EOL .
                       '</html>';
            case 'form':
                return '<form method="POST" action="{{ $action }}">' . PHP_EOL .
                       '    @csrf' . PHP_EOL .
                       '    <input type="text" name="name" placeholder="이름">' . PHP_EOL .
                       '    <input type="email" name="email" placeholder="이메일">' . PHP_EOL .
                       '    <button type="submit">제출</button>' . PHP_EOL .
                       '</form>';
            default:
                return '<div class="basic-template">' . PHP_EOL .
                       '    <h1>{{ $title }}</h1>' . PHP_EOL .
                       '    <p>{{ $content }}</p>' . PHP_EOL .
                       '</div>';
        }
    }
}
