<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 회원가입이 정상적으로 동작하는지 테스트
     * @test
     */
    public function 회원가입이_정상적으로_동작한다_테스트()
    {
        // Given: 회원가입 데이터 준비
        $userData = [
            'name' => '홍길동',
            'email' => 'test@gmail.com',
            'password' => 'test123!',
            'password_confirmation' => 'test123!'
        ];

        // When: 회원가입 API 호출
        $response = $this->post('/api/auth/signup', $userData);

        // Then: 성공 응답 확인
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => '회원가입이 완료되었습니다.'
        ]);
        
        // 사용자 생성 확인
        $this->assertDatabaseHas('users', [
            'name' => '홍길동',
            'email' => 'test@gmail.com'
        ]);
        
        // 토큰 포함 확인
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => ['id', 'name', 'email', 'created_at'],
                'token'
            ]
        ]);
    }

    /**
     * 로그인이 정상적으로 동작하는지 테스트
     * @test
     */
    public function 로그인이_정상적으로_동작한다_테스트()
    {
        // Given: 테스트 사용자 생성
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('test123!')
        ]);

        // When: 로그인 API 호출
        $response = $this->post('/api/auth/login', [
            'email' => 'test@gmail.com',
            'password' => 'test123!'
        ]);

        // Then: 성공 응답 확인
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => '로그인이 완료되었습니다.'
        ]);
        
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => ['id', 'name', 'email'],
                'token'
            ]
        ]);
    }

    /**
     * 잘못된 비밀번호로 로그인 실패하는지 테스트
     * @test
     */
    public function 잘못된_비밀번호로_로그인이_실패한다_테스트()
    {
        // Given: 테스트 사용자 생성
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('correct_password')
        ]);

        // When: 잘못된 비밀번호로 로그인 시도
        $response = $this->post('/api/auth/login', [
            'email' => 'test@gmail.com',
            'password' => 'wrong_password'
        ]);

        // Then: 401 Unauthorized 응답 확인
        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => '이메일 또는 비밀번호가 올바르지 않습니다.'
        ]);
    }

    /**
     * 로그아웃이 정상적으로 동작하는지 테스트
     * @test
     */
    public function 로그아웃이_정상적으로_동작한다_테스트()
    {
        // Given: 인증된 사용자 준비
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // When: 토큰으로 로그아웃 API 호출
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->post('/api/auth/logout');

        // Then: 성공 응답 확인
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => '로그아웃이 완료되었습니다.'
        ]);
    }

    /**
     * 인증되지 않은 사용자의 로그아웃 실패하는지 테스트
     * @test
     */
    public function 인증되지_않은_사용자의_로그아웃이_실패한다_테스트()
    {
        // When: 토큰 없이 로그아웃 시도
        $response = $this->post('/api/auth/logout');

        // Then: 401 Unauthorized 응답 확인
        $response->assertStatus(401);
    }

    /**
     * 회원가입 검증 실패 테스트
     * @test
     */
    public function 회원가입_검증_실패시_422_응답을_반환한다_테스트()
    {
        // Given: 잘못된 회원가입 데이터
        $invalidData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456'
        ];

        // When: 잘못된 데이터로 회원가입 시도
        $response = $this->post('/api/auth/signup', $invalidData);

        // Then: 422 검증 실패 응답 확인
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => '입력값 검증에 실패했습니다.'
        ]);
        
        $response->assertJsonStructure([
            'success',
            'message',
            'errors'
        ]);
    }
}