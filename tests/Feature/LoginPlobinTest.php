<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginPlobinTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 로그인이_정상적으로_처리된다_테스트
     * @test
     */
    public function 로그인이_정상적으로_처리된다_테스트()
    {
        // Given: 회원가입된 사용자
        $user = User::create([
            'name' => '홍길동',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password123!')
        ]);

        // When: 로그인 API 호출
        $response = $this->post('/api/auth/login', [
            'email' => 'test@gmail.com',
            'password' => 'password123!'
        ]);

        // Then: 성공 응답 확인
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => '로그인되었습니다.'
        ]);

        // 응답 데이터 구조 확인
        $responseData = $response->json();
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('user', $responseData['data']);
        $this->assertArrayHasKey('token', $responseData['data']);
    }

    /**
     * 잘못된_이메일로_로그인시_인증_실패가_발생한다_테스트
     * @test
     */
    public function 잘못된_이메일로_로그인시_인증_실패가_발생한다_테스트()
    {
        // Given: 회원가입된 사용자
        User::create([
            'name' => '홍길동',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password123!')
        ]);

        // When: 잘못된 이메일로 로그인 시도
        $response = $this->post('/api/auth/login', [
            'email' => 'wrong@gmail.com',
            'password' => 'password123!'
        ]);

        // Then: 인증 실패 응답 확인
        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => '이메일 또는 비밀번호가 올바르지 않습니다.'
        ]);
    }

    /**
     * 잘못된_비밀번호로_로그인시_인증_실패가_발생한다_테스트
     * @test
     */
    public function 잘못된_비밀번호로_로그인시_인증_실패가_발생한다_테스트()
    {
        // Given: 회원가입된 사용자
        User::create([
            'name' => '홍길동',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password123!')
        ]);

        // When: 잘못된 비밀번호로 로그인 시도
        $response = $this->post('/api/auth/login', [
            'email' => 'test@gmail.com',
            'password' => 'wrong_password!'
        ]);

        // Then: 인증 실패 응답 확인
        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => '이메일 또는 비밀번호가 올바르지 않습니다.'
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
            'email' => 'test@gmail.com'
            // password 누락
        ];

        // When: 로그인 API 호출
        $response = $this->post('/api/auth/login', $incompleteData);

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
        $invalidData = [
            'email' => 'invalid-email',
            'password' => 'password123!'
        ];

        // When: 로그인 API 호출
        $response = $this->post('/api/auth/login', $invalidData);

        // Then: 검증 실패 응답 확인
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => '검증에 실패했습니다.'
        ]);
    }

    /**
     * 로그인_레이트_리밋이_정상적으로_작동한다_테스트
     * @test
     */
    public function 로그인_레이트_리밋이_정상적으로_작동한다_테스트()
    {
        // Given: 회원가입된 사용자
        User::create([
            'name' => '홍길동',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password123!')
        ]);

        // 정상 범위 내 요청 (4회)
        for ($i = 0; $i < 4; $i++) {
            $response = $this->post('/api/auth/login', [
                'email' => 'test@gmail.com',
                'password' => 'password123!'
            ]);
            
            $this->assertEquals(200, $response->getStatusCode(), 
                "Rate limit should not trigger for request #{$i}");
        }

        // 5번째 요청도 성공해야 함
        $response = $this->post('/api/auth/login', [
            'email' => 'test@gmail.com',
            'password' => 'password123!'
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        // 6번째 요청에서 rate limit 발동 (정상적인 429 응답)
        $response = $this->post('/api/auth/login', [
            'email' => 'test@gmail.com',
            'password' => 'password123!'
        ]);
        $this->assertEquals(429, $response->getStatusCode());
        $response->assertJson([
            'success' => false
        ]);
    }
}