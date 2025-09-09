<?php

namespace App\Livewire\Sandbox\CustomScreens;

use Livewire\Component;

class CustomScreen1757421612 extends Component
{
    public $title = "화면";
    public $stats = [];
    public $charts = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        try {
            // 샘플 통계 데이터
            $this->stats = [
                'total' => 100,
                'active' => 85,
                'pending' => 15,
            ];
        } catch (\Exception $e) {
            session()->flash('error', '데이터를 불러오는데 실패했습니다.');
        }
    }

    public function render()
    {
        return view('livewire.sandbox.custom-screens.custom-screen-1757421612');
    }
}