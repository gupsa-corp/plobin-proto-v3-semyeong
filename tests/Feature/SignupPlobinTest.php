<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SignupPlobinTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 회원가입이_정상적으로_처리된다_테스트
     * @test
     */
    public function 회원가입이_정상적으로_처리된다_테스트()
    {
        // Given: 유효한 회원가입 정보
        $userData = [
            'name' => '홍길동',
            'email' => 'test@gmail.com',
            'password' => 'password123!',
            'password_confirmation' => 'password123!'
        ];

        // When: 회원가입 API 호출
        $response = $this->post('/api/auth/signup', $userData);

        // Then: 성공 응답 확인
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => '회원가입이 완료되었습니다.'
        ]);

        // 응답 데이터 구조 확인
        $responseData = $response->json();
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('user', $responseData['data']);
        $this->assertArrayHasKey('token', $responseData['data']);
        
        // 사용자 정보 확인
        $this->assertDatabaseHas('users', [
            'email' => 'test@gmail.com',
            'name' => '홍길동'
        ]);
    }

    /**
     * 필수_필드_누락시_검증_오류가_발생한다_테스트
     * @test
     */
    public function 필수_필드_누락시_검증_오류가_발생한다_테스트()
    {
        // Given: 필수 필드가 누락된 데이터
        $incompleteData = [
            'name' => '홍길동'
            // email과 password 누락
        ];

        // When: 회원가입 API 호출
        $response = $this->post('/api/auth/signup', $incompleteData);

        // Then: 검증 실패 응답 확인
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => '검증에 실패했습니다.'
        ]);
    }

    /**
     * 잘못된_이메일_형식에_대해_검증_오류가_발생한다_테스트
     * @test
     */
    public function 잘못된_이메일_형식에_대해_검증_오류가_발생한다_테스트()
    {
        // Given: 잘못된 이메일 형식
        $userData = [
            'name' => '홍길동',
            'email' => 'invalid-email',
            'password' => 'password123!',
            'password_confirmation' => 'password123!'
        ];

        // When: 회원가입 API 호출
        $response = $this->post('/api/auth/signup', $userData);

        // Then: 검증 실패 응답 확인
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => '검증에 실패했습니다.'
        ]);
    }

    /**
     * 비밀번호_확인이_일치하지_않으면_검증_오류가_발생한다_테스트
     * @test
     */
    public function 비밀번호_확인이_일치하지_않으면_검증_오류가_발생한다_테스트()
    {
        // Given: 비밀번호 확인이 일치하지 않는 데이터
        $userData = [
            'name' => '홍길동',
            'email' => 'test@gmail.com',
            'password' => 'password123!',
            'password_confirmation' => 'different_password!'
        ];

        // When: 회원가입 API 호출
        $response = $this->post('/api/auth/signup', $userData);

        // Then: 검증 실패 응답 확인
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => '검증에 실패했습니다.'
        ]);
    }

    /**
     * 회원가입_레이트_리밋이_정상적으로_작동한다_테스트
     * @test
     */
    public function 회원가입_레이트_리밋이_정상적으로_작동한다_테스트()
    {
        // 정상 범위 내 요청 (2회)
        for ($i = 0; $i < 2; $i++) {
            $response = $this->post('/api/auth/signup', [
                'name' => "홍길동{$i}",
                'email' => "test{$i}@gmail.com",
                'password' => 'password123!',
                'password_confirmation' => 'password123!'
            ]);
            
            $this->assertEquals(201, $response->getStatusCode(), 
                "Rate limit should not trigger for request #{$i}");
        }

        // 3번째 요청도 성공해야 함
        $response = $this->post('/api/auth/signup', [
            'name' => '홍길동3',
            'email' => 'test3@gmail.com',
            'password' => 'password123!',
            'password_confirmation' => 'password123!'
        ]);
        $this->assertEquals(201, $response->getStatusCode());

        // 4번째 요청에서 rate limit 발동 (정상적인 429 응답)
        $response = $this->post('/api/auth/signup', [
            'name' => '홍길동4',
            'email' => 'test4@gmail.com',
            'password' => 'password123!',
            'password_confirmation' => 'password123!'
        ]);
        $this->assertEquals(429, $response->getStatusCode());
        $response->assertJson([
            'success' => false
        ]);
    }
}