<?php

namespace App\Livewire\PlatformAdmin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class AuditLog extends Component
{
    use WithPagination;

    public $dateFilter = '';
    public $userFilter = '';
    public $actionFilter = '';
    public $logNameFilter = '';
    
    // 필터링 옵션
    public $perPage = 20;
    
    protected $queryString = [
        'dateFilter' => ['except' => ''],
        'userFilter' => ['except' => ''],
        'actionFilter' => ['except' => ''],
        'logNameFilter' => ['except' => ''],
    ];

    public function mount()
    {
        // 기본값 설정 - 최근 7일
        $this->dateFilter = 'week';
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
    }

    public function updatedUserFilter()
    {
        $this->resetPage();
    }

    public function updatedActionFilter()
    {
        $this->resetPage();
    }

    public function updatedLogNameFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->dateFilter = '';
        $this->userFilter = '';
        $this->actionFilter = '';
        $this->logNameFilter = '';
        $this->resetPage();
    }

    public function getActivitiesProperty()
    {
        $query = Activity::with(['causer'])
            ->orderBy('created_at', 'desc');

        // 날짜 필터
        if ($this->dateFilter) {
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', Carbon::now()->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', Carbon::now()->subMonth());
                    break;
                case 'year':
                    $query->where('created_at', '>=', Carbon::now()->subYear());
                    break;
            }
        }

        // 사용자 필터
        if ($this->userFilter) {
            $query->whereHasMorph('causer', [\App\Models\User::class], function ($q) {
                $q->where('email', 'like', '%' . $this->userFilter . '%')
                  ->orWhere('name', 'like', '%' . $this->userFilter . '%');
            });
        }

        // 액션 필터
        if ($this->actionFilter) {
            $query->where('description', 'like', '%' . $this->actionFilter . '%');
        }

        // 로그 이름 필터
        if ($this->logNameFilter) {
            $query->where('log_name', $this->logNameFilter);
        }

        return $query->paginate($this->perPage);
    }

    public function getLogNamesProperty()
    {
        return Activity::distinct()
            ->whereNotNull('log_name')
            ->pluck('log_name')
            ->sort()
            ->values();
    }

    public function getActivityTypeLabel($description)
    {
        $activityTypes = [
            'created' => ['label' => '생성', 'color' => 'green'],
            'updated' => ['label' => '수정', 'color' => 'blue'],
            'deleted' => ['label' => '삭제', 'color' => 'red'],
            'assigned' => ['label' => '할당', 'color' => 'purple'],
            'removed' => ['label' => '제거', 'color' => 'orange'],
            'logged in' => ['label' => '로그인', 'color' => 'gray'],
            'logged out' => ['label' => '로그아웃', 'color' => 'gray'],
        ];

        foreach ($activityTypes as $key => $type) {
            if (str_contains(strtolower($description), $key)) {
                return $type;
            }
        }

        return ['label' => '기타', 'color' => 'indigo'];
    }

    public function getFormattedProperties($properties)
    {
        if (empty($properties)) {
            return null;
        }

        $formatted = [];
        
        foreach ($properties as $key => $value) {
            if ($key === 'attributes' && is_array($value)) {
                $formatted['변경 후'] = $value;
            } elseif ($key === 'old' && is_array($value)) {
                $formatted['변경 전'] = $value;
            } else {
                $formatted[$key] = $value;
            }
        }

        return $formatted;
    }

    public function exportToCSV()
    {
        $activities = $this->activities->items();
        
        $csvData = [];
        $csvData[] = ['ID', '시간', '사용자', '액션', '대상', '변경사항', 'IP 주소'];
        
        foreach ($activities as $activity) {
            $causer = $activity->causer ? $activity->causer->email : '시스템';
            $subject = $activity->subject_type ? class_basename($activity->subject_type) . ' #' . $activity->subject_id : '-';
            $properties = $activity->properties ? json_encode($activity->properties, JSON_UNESCAPED_UNICODE) : '';
            $ipAddress = $activity->properties['ip_address'] ?? '-';
            
            $csvData[] = [
                $activity->id,
                $activity->created_at->format('Y-m-d H:i:s'),
                $causer,
                $activity->description,
                $subject,
                $properties,
                $ipAddress
            ];
        }

        $filename = 'audit_log_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $this->dispatch('download-csv', [
            'filename' => $filename,
            'data' => $csvData
        ]);
    }

    public function render()
    {
        return view('900-page-platform-admin.components.923-audit-log', [
            'activities' => $this->activities,
            'logNames' => $this->logNames,
        ]);
    }
}