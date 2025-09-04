<?php

namespace App\Http\AuthUser\SignupPlobin;

use App\Http\Controllers\ApiController;
use App\Models\User;

class Controller extends ApiController
{
    public function __invoke(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'password' => $request->password,
        ]);

        // 회원가입과 동시에 로그인 토큰 발급
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->created([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            'token' => $token
        ], '회원가입이 완료되었습니다.');
    }
}
