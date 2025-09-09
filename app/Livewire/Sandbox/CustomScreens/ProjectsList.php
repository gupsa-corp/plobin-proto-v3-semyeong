<?php

namespace App\Livewire\Sandbox\CustomScreens;

use Livewire\Component;

class ProjectsList extends Component
{
    public $title = "프로젝트 목록";
    public $users = [];
    public $search = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        try {
            // 샘플 데이터 - 프로젝트 목록
            $this->users = [
                ['id' => 1, 'name' => '프로젝트 A', 'email' => 'project-a@example.com'],
                ['id' => 2, 'name' => '프로젝트 B', 'email' => 'project-b@example.com'],
                ['id' => 3, 'name' => '프로젝트 C', 'email' => 'project-c@example.com'],
            ];
        } catch (\Exception $e) {
            session()->flash('error', '데이터를 불러오는데 실패했습니다.');
        }
    }

    public function render()
    {
        return view('livewire.sandbox.custom-screens.projects-list');
    }
}