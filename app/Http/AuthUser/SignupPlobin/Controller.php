<?php

namespace App\Http\AuthUser\SignupPlobin;

use App\Http\Controllers\ApiController;
use App\Http\Traits\{HasRateLimit, HasCache, HasSecurity};
use App\Models\User;

class Controller extends ApiController
{
    use HasRateLimit, HasCache, HasSecurity;

    public function __invoke(Request $request)
    {
        $this->checkRateLimit($request, null, 3, 5);

        $normalizedEmail = $this->normalizeEmail($request->email);

        $user = User::create([
            'name' => $request->name,
            'email' => $normalizedEmail,
            'password' => $request->password,
        ]);

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
