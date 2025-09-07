<?php

namespace App\Livewire\PlatformAdmin\Dashboard;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Organization;
use App\Models\User;
use App\Models\Project;

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
        try {
            $activities = collect();

            // 최근 생성된 조직들
            $recentOrganizations = Organization::latest('created_at')
                ->limit(2)
                ->get()
                ->map(function ($org) {
                    return [
                        'id' => $org->id,
                        'type' => 'organization',
                        'icon_color' => 'bg-blue-500',
                        'title' => '새 조직이 생성되었습니다',
                        'description' => $org->name,
                        'time' => $this->getTimeAgo($org->created_at),
                        'user' => null,
                        'created_at' => $org->created_at
                    ];
                });

            // 최근 가입한 사용자들
            $recentUsers = User::latest('created_at')
                ->limit(2)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'type' => 'user',
                        'icon_color' => 'bg-green-500',
                        'title' => '사용자가 가입했습니다',
                        'description' => $user->name . ' (' . $user->email . ')',
                        'time' => $this->getTimeAgo($user->created_at),
                        'user' => $user->email,
                        'created_at' => $user->created_at
                    ];
                });

            // 최근 생성된 프로젝트들
            $recentProjects = Project::latest('created_at')
                ->limit(2)
                ->get()
                ->map(function ($project) {
                    return [
                        'id' => $project->id,
                        'type' => 'project',
                        'icon_color' => 'bg-purple-500',
                        'title' => '새 프로젝트가 생성되었습니다',
                        'description' => $project->name,
                        'time' => $this->getTimeAgo($project->created_at),
                        'user' => null,
                        'created_at' => $project->created_at
                    ];
                });

            // 모든 활동을 합치고 시간순으로 정렬
            $activities = $activities
                ->merge($recentOrganizations)
                ->merge($recentUsers)
                ->merge($recentProjects)
                ->sortByDesc('created_at')
                ->take($this->maxActivities)
                ->values();

            $this->activities = $activities->toArray();

        } catch (\Exception $e) {
            // 오류 발생시 빈 배열 반환
            $this->activities = [];
        }
    }

    public function refreshActivities()
    {
        $this->loadActivities();
        $this->dispatch('activities-updated');
    }

    private function getTimeAgo($timestamp)
    {
        try {
            $carbon = Carbon::parse($timestamp);
            return $carbon->diffForHumans();
        } catch (\Exception $e) {
            return '알 수 없음';
        }
    }

    public function render()
    {
        return view('900-page-platform-admin.901-page-dashboard.300-livewire-block-recent-activity');
    }
}