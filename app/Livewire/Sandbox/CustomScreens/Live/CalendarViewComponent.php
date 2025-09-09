<?php

namespace App\Livewire\Sandbox\CustomScreens\Live;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalendarViewComponent extends Component
{
    public $currentMonth;
    public $currentYear;
    public $calendarDays = [];
    public $events = [];
    public $stats = [];
    public $selectedDate = null;
    public $viewMode = 'month'; // month, week, day

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->generateCalendar();
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.sandbox.custom-screens.live.calendar-view-component');
    }

    public function loadData()
    {
        try {
            $sandboxConnection = 'sandbox';
            
            // 프로젝트 이벤트 데이터 로드
            $projects = DB::connection($sandboxConnection)
                ->table('projects')
                ->leftJoin('users', 'projects.created_by', '=', 'users.id')
                ->leftJoin('organizations', 'projects.organization_id', '=', 'organizations.id')
                ->select(
                    'projects.*',
                    'users.name as created_by_name',
                    'organizations.name as organization_name'
                )
                ->get();

            $this->events = [];
            
            foreach ($projects as $project) {
                // 프로젝트 시작일
                $startDate = Carbon::parse($project->start_date ?? $project->created_at);
                $this->events[] = [
                    'date' => $startDate->format('Y-m-d'),
                    'title' => $project->name . ' 시작',
                    'type' => 'start',
                    'project_id' => $project->id,
                    'description' => $project->description,
                    'organization' => $project->organization_name ?? '-'
                ];

                // 프로젝트 종료일 (예정)
                $endDate = Carbon::parse($project->end_date ?? $startDate->copy()->addDays(30));
                $this->events[] = [
                    'date' => $endDate->format('Y-m-d'),
                    'title' => $project->name . ' 완료 예정',
                    'type' => 'end',
                    'project_id' => $project->id,
                    'description' => $project->description,
                    'organization' => $project->organization_name ?? '-'
                ];

                // 마일스톤 (중간 지점)
                if ($startDate->diffInDays($endDate) > 14) {
                    $milestoneDate = $startDate->copy()->addDays($startDate->diffInDays($endDate) / 2);
                    $this->events[] = [
                        'date' => $milestoneDate->format('Y-m-d'),
                        'title' => $project->name . ' 중간 점검',
                        'type' => 'milestone',
                        'project_id' => $project->id,
                        'description' => '프로젝트 진행 상황 점검',
                        'organization' => $project->organization_name ?? '-'
                    ];
                }
            }

            // 통계 데이터
            $this->stats = [
                'total_events' => count($this->events),
                'this_month_events' => count(array_filter($this->events, function($event) {
                    $eventDate = Carbon::parse($event['date']);
                    return $eventDate->year === $this->currentYear && $eventDate->month === $this->currentMonth;
                })),
                'upcoming_events' => count(array_filter($this->events, function($event) {
                    return Carbon::parse($event['date'])->isAfter(now());
                })),
                'overdue_events' => count(array_filter($this->events, function($event) {
                    return Carbon::parse($event['date'])->isPast() && $event['type'] === 'end';
                }))
            ];
            
        } catch (\Exception $e) {
            // 기본값 설정
            $this->events = [
                [
                    'date' => now()->format('Y-m-d'),
                    'title' => '웹사이트 리뉴얼 시작',
                    'type' => 'start',
                    'project_id' => 1,
                    'description' => '새로운 웹사이트 디자인 및 개발 시작',
                    'organization' => '테크 스타트업'
                ],
                [
                    'date' => now()->addDays(3)->format('Y-m-d'),
                    'title' => '모바일 앱 중간 점검',
                    'type' => 'milestone',
                    'project_id' => 2,
                    'description' => '모바일 앱 개발 진행 상황 점검',
                    'organization' => '디지털 에이전시'
                ],
                [
                    'date' => now()->addDays(7)->format('Y-m-d'),
                    'title' => 'API 플랫폼 완료 예정',
                    'type' => 'end',
                    'project_id' => 3,
                    'description' => 'REST API 개발 완료 예정',
                    'organization' => '클라우드 솔루션'
                ],
                [
                    'date' => now()->addDays(14)->format('Y-m-d'),
                    'title' => '데이터베이스 마이그레이션 시작',
                    'type' => 'start',
                    'project_id' => 4,
                    'description' => 'DB 스키마 변경 작업 시작',
                    'organization' => '테크 스타트업'
                ]
            ];

            $this->stats = [
                'total_events' => 12,
                'this_month_events' => 6,
                'upcoming_events' => 8,
                'overdue_events' => 2
            ];
        }
    }

    private function generateCalendar()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        // 달력 시작일 (월요일부터 시작하도록 조정)
        $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        
        // 달력 종료일 (일요일까지)
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);
        
        $this->calendarDays = [];
        for ($date = $startOfCalendar->copy(); $date->lte($endOfCalendar); $date->addDay()) {
            $this->calendarDays[] = [
                'date' => $date->copy(),
                'isCurrentMonth' => $date->month === $this->currentMonth,
                'isToday' => $date->isToday(),
                'events' => $this->getEventsForDate($date->format('Y-m-d'))
            ];
        }
    }

    private function getEventsForDate($date)
    {
        return array_filter($this->events, function($event) use ($date) {
            return $event['date'] === $date;
        });
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendar();
        $this->loadData();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->generateCalendar();
        $this->loadData();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function refreshData()
    {
        $this->loadData();
        $this->generateCalendar();
        $this->dispatch('data-refreshed');
    }
}