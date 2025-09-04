<?php

namespace App\Http\AuthUser\CheckEmail;

use App\Http\Controllers\ApiController;
use App\Http\Traits\{HasRateLimit, HasCache, HasSecurity};
use App\Models\User;

class Controller extends ApiController
{
    use HasRateLimit, HasCache, HasSecurity;

    public function __invoke(Request $request)
    {
        $this->checkRateLimit($request);
        
        $normalizedEmail = $this->normalizeEmail($request->email);
        
        $exists = $this->cacheRemember(
            $this->makeCacheKey('email_check', $normalizedEmail),
            300,
            fn() => User::whereRaw('LOWER(email) = ?', [$normalizedEmail])->exists()
        );
        
        $this->preventTimingAttack();
        
        return $this->success([
            'available' => !$exists,
            'message' => $exists ? '이미 사용중인 이메일입니다.' : '사용 가능한 이메일입니다.'
        ]);
    }
}
