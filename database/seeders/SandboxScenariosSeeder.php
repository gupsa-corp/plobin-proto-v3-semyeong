<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SandboxScenario;
use App\Models\SandboxScenarioRequirement;

class SandboxScenariosSeeder extends Seeder
{
    public function run(): void
    {
        // 사용자 인증 시나리오
        $authScenario = SandboxScenario::create([
            'title' => '사용자 인증 시스템 구현',
            'description' => 'JWT 기반 사용자 인증 및 권한 관리 시스템을 구현합니다. 회원가입, 로그인, 로그아웃, 권한 관리 기능을 포함합니다.',
            'priority' => 'high',
            'status' => 'in-progress',
            'sort_order' => 1
        ]);

        $authRequirements = [
            'JWT 토큰 생성 및 검증 기능 구현',
            '사용자 회원가입 API 개발',
            '로그인/로그아웃 API 개발',
            '권한별 미들웨어 구현',
            '패스워드 해싱 및 검증',
        ];

        foreach ($authRequirements as $index => $requirement) {
            SandboxScenarioRequirement::create([
                'sandbox_scenario_id' => $authScenario->id,
                'content' => $requirement,
                'completed' => $index < 2, // 처음 2개는 완료된 상태
                'sort_order' => $index + 1
            ]);
        }

        // 파일 업로드 시나리오
        $fileScenario = SandboxScenario::create([
            'title' => '파일 업로드 및 관리 시스템',
            'description' => '안전한 파일 업로드, 저장, 조회 시스템을 구현합니다. 이미지 리사이징, 파일 타입 검증, 권한 기반 접근 제어를 포함합니다.',
            'priority' => 'medium',
            'status' => 'todo',
            'sort_order' => 2
        ]);

        $fileRequirements = [
            '멀티파트 파일 업로드 API 구현',
            '파일 타입 및 크기 검증',
            '이미지 자동 리사이징 기능',
            '파일 접근 권한 관리',
            '파일 메타데이터 저장',
            '파일 삭제 및 정리 스케줄러'
        ];

        foreach ($fileRequirements as $index => $requirement) {
            SandboxScenarioRequirement::create([
                'sandbox_scenario_id' => $fileScenario->id,
                'content' => $requirement,
                'completed' => false,
                'sort_order' => $index + 1
            ]);
        }

        // API 문서화 시나리오
        $docScenario = SandboxScenario::create([
            'title' => 'API 자동 문서화 시스템',
            'description' => 'OpenAPI/Swagger 기반 자동 API 문서 생성 및 테스트 인터페이스를 구현합니다.',
            'priority' => 'low',
            'status' => 'done',
            'sort_order' => 3
        ]);

        $docRequirements = [
            'Swagger UI 통합',
            'API 애노테이션 추가',
            '자동 스키마 생성',
            'API 테스트 인터페이스 구현'
        ];

        foreach ($docRequirements as $index => $requirement) {
            SandboxScenarioRequirement::create([
                'sandbox_scenario_id' => $docScenario->id,
                'content' => $requirement,
                'completed' => true, // 모두 완료된 상태
                'sort_order' => $index + 1
            ]);
        }

        // 데이터베이스 최적화 시나리오
        $dbScenario = SandboxScenario::create([
            'title' => '데이터베이스 성능 최적화',
            'description' => '쿼리 최적화, 인덱스 설계, 커넥션 풀 관리를 통한 데이터베이스 성능 향상 작업입니다.',
            'priority' => 'high',
            'status' => 'cancelled',
            'sort_order' => 4
        ]);

        $dbRequirements = [
            '슬로우 쿼리 분석 도구 설정',
            '인덱스 최적화 계획 수립',
            '커넥션 풀 설정 조정',
            '쿼리 캐싱 구현',
            '데이터베이스 모니터링 설정'
        ];

        foreach ($dbRequirements as $index => $requirement) {
            SandboxScenarioRequirement::create([
                'sandbox_scenario_id' => $dbScenario->id,
                'content' => $requirement,
                'completed' => $index === 0, // 첫 번째만 완료
                'sort_order' => $index + 1
            ]);
        }

        // 실시간 알림 시스템 시나리오
        $notificationScenario = SandboxScenario::create([
            'title' => '실시간 알림 시스템 구현',
            'description' => 'WebSocket과 푸시 알림을 활용한 실시간 알림 시스템을 구축합니다. 이메일, SMS, 브라우저 푸시를 지원합니다.',
            'priority' => 'medium',
            'status' => 'in-progress',
            'sort_order' => 5
        ]);

        $notificationRequirements = [
            'WebSocket 서버 구현',
            '푸시 알림 서비스 연동',
            '이메일 템플릿 시스템',
            'SMS 발송 기능',
            '알림 설정 관리 UI'
        ];

        foreach ($notificationRequirements as $index => $requirement) {
            $parentReq = SandboxScenarioRequirement::create([
                'sandbox_scenario_id' => $notificationScenario->id,
                'content' => $requirement,
                'completed' => $index < 2, // 처음 2개 완료
                'sort_order' => $index + 1
            ]);

            // WebSocket 서버 구현에 하위 요구사항 추가
            if ($index === 0) {
                $subRequirements = [
                    'Socket.IO 서버 설정',
                    '클라이언트 연결 관리',
                    '브로드캐스트 기능 구현'
                ];

                foreach ($subRequirements as $subIndex => $subReq) {
                    SandboxScenarioRequirement::create([
                        'sandbox_scenario_id' => $notificationScenario->id,
                        'parent_id' => $parentReq->id,
                        'content' => $subReq,
                        'completed' => $subIndex < 1, // 첫 번째만 완료
                        'sort_order' => $subIndex + 1
                    ]);
                }
            }
        }
    }
}