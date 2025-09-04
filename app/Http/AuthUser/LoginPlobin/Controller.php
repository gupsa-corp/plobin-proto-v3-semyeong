<?php

namespace App\Http\AuthUser\LoginPlobin;

use App\Http\Controllers\ApiController;
use App\Http\Middleware\WebAuthSecurityMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Controller extends ApiController
{
    public function __invoke(Request $request)
    {
        $user = User::whereRaw('LOWER(email) = ?', [$request->email])->first();

        // 타이밍 어택 방지
        WebAuthSecurityMiddleware::preventTimingAttack();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw \App\Exceptions\ApiException::unauthorized('이메일 또는 비밀번호가 올바르지 않습니다.');
        }

        // 기존 토큰들 삭제
        $user->tokens()->delete();

        // 새 토큰 생성
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token
        ], '로그인이 완료되었습니다.');
    }
}
