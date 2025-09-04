<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class LogoutPlobinTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 로그아웃이_정상적으로_처리된다_테스트
     * @test
     */
    public function 로그아웃이_정상적으로_처리된다_테스트()
    {
        // Given: 로그인된 사용자
        $user = User::create([
            'name' => '홍길동',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password123!')
        ]);
        
        $token = $user->createToken('auth-token')->plainTextToken;

        // When: 로그아웃 API 호출
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->post('/api/auth/logout');

        // Then: 성공 응답 확인
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => '로그아웃되었습니다.'
        ]);

        // 토큰이 무효화되었는지 확인
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class
        ]);
    }

    /**
     * 인증되지_않은_사용자의_로그아웃_요청시_인증_오류가_발생한다_테스트
     * @test
     */
    public function 인증되지_않은_사용자의_로그아웃_요청시_인증_오류가_발생한다_테스트()
    {
        // When: 인증 토큰 없이 로그아웃 API 호출
        $response = $this->post('/api/auth/logout');

        // Then: 인증 실패 응답 확인
        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => '인증이 필요합니다.'
        ]);
    }

    /**
     * 잘못된_토큰으로_로그아웃_요청시_인증_오류가_발생한다_테스트
     * @test
     */
    public function 잘못된_토큰으로_로그아웃_요청시_인증_오류가_발생한다_테스트()
    {
        // When: 잘못된 토큰으로 로그아웃 API 호출
        $response = $this->withHeader('Authorization', 'Bearer invalid-token')
                         ->post('/api/auth/logout');

        // Then: 인증 실패 응답 확인
        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => '인증이 필요합니다.'
        ]);
    }

    /**
     * 이미_무효화된_토큰으로_로그아웃_요청시_인증_오류가_발생한다_테스트
     * @test
     */
    public function 이미_무효화된_토큰으로_로그아웃_요청시_인증_오류가_발생한다_테스트()
    {
        // Given: 사용자와 토큰 생성 후 삭제
        $user = User::create([
            'name' => '홍길동',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password123!')
        ]);
        
        $token = $user->createToken('auth-token');
        $plainTextToken = $token->plainTextToken;
        
        // 토큰 무효화
        $token->accessToken->delete();

        // When: 무효화된 토큰으로 로그아웃 API 호출
        $response = $this->withHeader('Authorization', 'Bearer ' . $plainTextToken)
                         ->post('/api/auth/logout');

        // Then: 인증 실패 응답 확인
        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'message' => '인증이 필요합니다.'
        ]);
    }

    /**
     * Sanctum을_통한_로그아웃이_정상적으로_처리된다_테스트
     * @test
     */
    public function Sanctum을_통한_로그아웃이_정상적으로_처리된다_테스트()
    {
        // Given: Sanctum으로 인증된 사용자
        $user = User::create([
            'name' => '홍길동',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password123!')
        ]);
        
        Sanctum::actingAs($user);

        // When: 로그아웃 API 호출
        $response = $this->post('/api/auth/logout');

        // Then: 성공 응답 확인
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => '로그아웃되었습니다.'
        ]);
    }
}