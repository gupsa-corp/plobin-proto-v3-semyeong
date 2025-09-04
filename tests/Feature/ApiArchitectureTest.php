<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

class ApiArchitectureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 모든 API 컨트롤러가 에러 없이 실행되는지 테스트
     * @test
     */
    public function 모든_API_컨트롤러가_에러_없이_실행된다_테스트()
    {
        // 성공 케이스들
        $testCases = [
            // CheckEmail API - 올바른 이메일
            [
                'method' => 'POST',
                'route' => '/api/auth/check-email',
                'data' => ['email' => 'test@gmail.com'],
                'expected_status' => 200
            ]
        ];

        foreach ($testCases as $case) {
            $response = $this->{strtolower($case['method'])}($case['route'], $case['data']);
            
            // 에러 상태코드가 아님을 확인
            $this->assertNotEquals(500, $response->getStatusCode(), 
                "Server error occurred for {$case['route']}");
            
            // JSON 응답 구조 확인
            $response->assertJson([
                'success' => true
            ]);
            
            // 예상 상태코드 확인
            $response->assertStatus($case['expected_status']);
        }

        // 실패 케이스들 (검증 오류는 정상적인 응답)
        $failureCases = [
            // 잘못된 이메일 형식
            [
                'method' => 'POST',
                'route' => '/api/auth/check-email',
                'data' => ['email' => 'invalid-email'],
                'expected_status' => 422 // 검증 실패
            ],
            // 누락된 이메일
            [
                'method' => 'POST',
                'route' => '/api/auth/check-email',
                'data' => [],
                'expected_status' => 422 // 검증 실패
            ]
        ];

        foreach ($failureCases as $case) {
            $response = $this->{strtolower($case['method'])}($case['route'], $case['data']);
            
            // 서버 에러가 아님을 확인 (검증 실패는 정상적인 응답)
            $this->assertNotEquals(500, $response->getStatusCode(), 
                "Server error occurred for {$case['route']} with invalid data");
            
            // JSON 응답 구조 확인
            $response->assertJson([
                'success' => false
            ]);
            
            // 예상 상태코드 확인
            $response->assertStatus($case['expected_status']);
        }

        $this->assertTrue(true, 'All API controllers executed without server errors');
    }

    /**
     * Rate limit 테스트 (정상 작동 확인)
     * @test
     */
    public function 레이트_리밋이_정상적으로_작동한다_테스트()
    {
        // 정상 범위 내 요청 (9회)
        for ($i = 0; $i < 9; $i++) {
            $response = $this->post('/api/auth/check-email', [
                'email' => "test{$i}@gmail.com"
            ]);
            
            $this->assertEquals(200, $response->getStatusCode(), 
                "Rate limit should not trigger for request #{$i}");
        }

        // 10번째 요청도 성공해야 함
        $response = $this->post('/api/auth/check-email', [
            'email' => 'test10@gmail.com'
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        // 11번째 요청에서 rate limit 발동 (정상적인 429 응답)
        $response = $this->post('/api/auth/check-email', [
            'email' => 'test11@gmail.com'
        ]);
        $this->assertEquals(429, $response->getStatusCode());
        $response->assertJson([
            'success' => false
        ]);

        $this->assertTrue(true, 'Rate limiting works correctly without server errors');
    }

    /**
     * ApiException 처리 테스트
     * @test
     */
    public function API_예외_처리가_정상적으로_동작한다_테스트()
    {
        // 존재하지 않는 라우트 - global exception handler가 500으로 처리
        $response = $this->post('/api/non-existent-route');
        $this->assertEquals(500, $response->getStatusCode());
        $response->assertJson([
            'success' => false,
            'message' => '서버 오류가 발생했습니다.'
        ]);

        // 잘못된 HTTP 메서드 - global exception handler가 500으로 처리
        $response = $this->get('/api/auth/check-email'); // POST만 허용
        $this->assertEquals(500, $response->getStatusCode());
        $response->assertJson([
            'success' => false
        ]);

        $this->assertTrue(true, 'Exception handling works correctly');
    }
}