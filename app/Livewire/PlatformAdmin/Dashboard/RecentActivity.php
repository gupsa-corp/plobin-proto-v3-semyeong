<?php

namespace App\Livewire\PlatformAdmin\Dashboard;

use Livewire\Component;
use Carbon\Carbon;

class RecentActivity extends Component
{
    public $activities = [];
    public $maxActivities = 4;

    public function mount()
    {
        $this->loadActivities();
    }

    public function loadActivities()
    {
        // TODO: 실제 활동 로그 모델에서 데이터 로드
        // 현재는 더미 데이터 사용
        $this->activities = [
            [
                'id' => 1,
                'type' => 'organization',
                'icon_color' => 'bg-blue-500',
                'title' => '새 조직이 생성되었습니다',
                'description' => '테크스타트업코리아',
                'time' => '2분 전',
                'user' => null
            ],
            [
                'id' => 2,
                'type' => 'user',
                'icon_color' => 'bg-green-500',
                'title' => '사용자가 가입했습니다',
                'description' => '김개발 (kim@example.com)',
                'time' => '5분 전',
                'user' => 'kim@example.com'
            ],
            [
                'id' => 3,
                'type' => 'project',
                'icon_color' => 'bg-purple-500',
                'title' => '새 프로젝트가 생성되었습니다',
                'description' => 'AI 챗봇 프로젝트',
                'time' => '10분 전',
                'user' => null
            ],
            [
                'id' => 4,
                'type' => 'system',
                'icon_color' => 'bg-yellow-500',
                'title' => '시스템 업데이트 완료',
                'description' => '버전 2.1.0',
                'time' => '1시간 전',
                'user' => null
            ]
        ];
    }

    public function refreshActivities()
    {
        $this->loadActivities();
        $this->dispatch('activities-updated');
    }

    public function getTimeAgoAttribute($timestamp)
    {
        // TODO: 실제 타임스탬프 처리
        return $timestamp;
    }

    public function render()
    {
        return view('900-page-platform-admin.901-page-dashboard.300-livewire-block-recent-activity');
    }
}