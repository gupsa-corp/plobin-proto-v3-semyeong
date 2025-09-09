<?php

namespace App\Livewire\Sandbox\CustomScreens\Renderer;

use Livewire\Component as LivewireComponent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

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
        // 실제 샌드박스 데이터베이스에서 데이터 가져오기
        $sampleData = $this->getSandboxData();
        
        $sampleData['title'] = $this->screen['title'] ?? '샘플 화면';
        $sampleData['description'] = $this->screen['description'] ?? '이것은 미리보기입니다';

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

    private function getSandboxData()
    {
        try {
            // 샌드박스 데이터베이스 연결 설정 (config/database.php의 sandbox 연결 사용)
            $sandboxConnection = 'sandbox';
            
            // 실제 데이터 가져오기
            $users = DB::connection($sandboxConnection)->table('users')->limit(10)->get()->toArray();
            $projects = DB::connection($sandboxConnection)->table('projects')->limit(10)->get()->toArray();
            $organizations = DB::connection($sandboxConnection)->table('organizations')->limit(10)->get()->toArray();
            
            // 통계 데이터 계산
            $userCount = DB::connection($sandboxConnection)->table('users')->count();
            $activeUserCount = DB::connection($sandboxConnection)->table('users')->where('email_verified_at', '!=', null)->count();
            $projectCount = DB::connection($sandboxConnection)->table('projects')->count();
            $organizationCount = DB::connection($sandboxConnection)->table('organizations')->count();
            
            return [
                'users' => collect($users)->map(function($user) {
                    return [
                        'id' => $user->id ?? 0,
                        'name' => $user->name ?? '사용자',
                        'email' => $user->email ?? 'user@example.com',
                        'status' => ($user->email_verified_at ?? null) ? 'active' : 'inactive',
                        'created_at' => $user->created_at ?? now()
                    ];
                })->toArray(),
                'projects' => collect($projects)->map(function($project) {
                    return [
                        'id' => $project->id ?? 0,
                        'name' => $project->name ?? '프로젝트',
                        'status' => $project->status ?? 'active',
                        'progress' => rand(10, 100),
                        'created_at' => $project->created_at ?? now()
                    ];
                })->toArray(),
                'organizations' => collect($organizations)->map(function($org) {
                    return [
                        'id' => $org->id ?? 0,
                        'name' => $org->name ?? '조직',
                        'created_at' => $org->created_at ?? now()
                    ];
                })->toArray(),
                'stats' => [
                    'total_users' => $userCount,
                    'active_users' => $activeUserCount,
                    'total_projects' => $projectCount,
                    'completed_projects' => collect($projects)->where('status', 'completed')->count(),
                    'total_organizations' => $organizationCount
                ]
            ];
            
        } catch (\Exception $e) {
            // 데이터베이스 연결 실패시 기본 샘플 데이터 반환
            return [
                'users' => [
                    ['id' => 1, 'name' => '홍길동', 'email' => 'hong@example.com', 'status' => 'active', 'created_at' => now()],
                    ['id' => 2, 'name' => '김철수', 'email' => 'kim@example.com', 'status' => 'inactive', 'created_at' => now()],
                    ['id' => 3, 'name' => '이영희', 'email' => 'lee@example.com', 'status' => 'active', 'created_at' => now()]
                ],
                'projects' => [
                    ['id' => 1, 'name' => '프로젝트 A', 'status' => 'active', 'progress' => 75, 'created_at' => now()],
                    ['id' => 2, 'name' => '프로젝트 B', 'status' => 'pending', 'progress' => 30, 'created_at' => now()],
                    ['id' => 3, 'name' => '프로젝트 C', 'status' => 'completed', 'progress' => 100, 'created_at' => now()]
                ],
                'organizations' => [
                    ['id' => 1, 'name' => '테스트 조직 1', 'created_at' => now()],
                    ['id' => 2, 'name' => '테스트 조직 2', 'created_at' => now()]
                ],
                'stats' => [
                    'total_users' => 156,
                    'active_users' => 142,
                    'total_projects' => 23,
                    'completed_projects' => 18,
                    'total_organizations' => 5
                ]
            ];
        }
    }

    public function refreshPreview()
    {
        $this->renderScreen();
        $this->dispatch('preview-updated');
    }
}