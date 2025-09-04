<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 이메일 체크가 정상적으로 작동한다 테스트
     * @test
     */
    public function 이메일_체크가_정상적으로_작동한다_테스트()
    {
        // Given: 사용 가능한 이메일
        $email = 'available@gmail.com';
        
        // When: 이메일 체크 API 호출
        $response = $this->post('/api/auth/check-email', ['email' => $email]);
        
        // Then: 성공 응답 확인
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => '사용 가능한 이메일입니다.'
        ]);
    }

    /**
     * 이메일 형식 검증이 올바르게 작동한다 테스트
     * @test
     */
    public function 이메일_형식_검증이_올바르게_작동한다_테스트()
    {
        // Given: 잘못된 이메일 형식
        $invalidEmail = 'invalid-email';
        
        // When: 이메일 체크 API 호출
        $response = $this->post('/api/auth/check-email', ['email' => $invalidEmail]);
        
        // Then: 검증 실패 응답 확인
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => '검증에 실패했습니다.'
        ]);
    }

    /**
     * 이메일 누락시 검증 오류가 발생한다 테스트
     * @test
     */
    public function 이메일_누락시_검증_오류가_발생한다_테스트()
    {
        // When: 이메일 없이 API 호출
        $response = $this->post('/api/auth/check-email', []);
        
        // Then: 검증 실패 응답 확인
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => '검증에 실패했습니다.'
        ]);
    }

    /**
     * 레이트_리밋이_정상적으로_작동한다_테스트
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
    }
}