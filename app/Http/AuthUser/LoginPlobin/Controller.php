<?php

namespace App\Http\AuthUser\LoginPlobin;

use App\Http\Controllers\ApiController;
use App\Http\Traits\{HasRateLimit, HasCache, HasSecurity};
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Controller extends ApiController
{
    use HasRateLimit, HasCache, HasSecurity;

    public function __invoke(Request $request)
    {
        $this->checkRateLimit($request, null, 5, 1); // 1분에 5회

        $normalizedEmail = $this->normalizeEmail($request->email);

        $user = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])->first();

        $this->preventTimingAttack();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw \App\Exceptions\ApiException::unauthorized('이메일 또는 비밀번호가 올바르지 않습니다.');
        }

        $user->tokens()->delete();

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
