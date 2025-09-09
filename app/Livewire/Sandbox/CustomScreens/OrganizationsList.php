<?php

namespace App\Livewire\Sandbox\CustomScreens;

use Livewire\Component;

class OrganizationsList extends Component
{
    public $title = "조직 목록";
    public $organizations = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        try {
            // 샘플 데이터 - 실제로는 함수나 DB에서 가져옴
            $this->organizations = [
                [
                    'name' => '개발팀',
                    'description' => '소프트웨어 개발 및 유지보수',
                    'created_at' => '2024-01-15'
                ],
                [
                    'name' => '마케팅팀',
                    'description' => '제품 홍보 및 마케팅 전략',
                    'created_at' => '2024-01-20'
                ],
                [
                    'name' => '운영팀',
                    'description' => '시스템 운영 및 관리',
                    'created_at' => '2024-01-25'
                ]
            ];
        } catch (\Exception $e) {
            // 에러 처리
            session()->flash('error', '데이터를 불러오는데 실패했습니다.');
        }
    }

    public function render()
    {
        return view('livewire.sandbox.custom-screens.organizations-list');
    }
}