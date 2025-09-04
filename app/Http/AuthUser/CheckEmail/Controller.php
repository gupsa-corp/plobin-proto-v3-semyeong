<?php

namespace App\Http\AuthUser\CheckEmail;

use App\Http\Controllers\ApiController;
use App\Http\Middleware\WebAuthSecurityMiddleware;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class Controller extends ApiController
{
    public function __invoke(Request $request)
    {
        // 이메일은 SecurityMiddleware에서 이미 정규화됨
        $cacheKey = 'email_check:' . $request->email;
        
        $exists = Cache::remember($cacheKey, 300, function () use ($request) {
            return User::whereRaw('LOWER(email) = ?', [$request->email])->exists();
        });
        
        // 타이밍 어택 방지
        WebAuthSecurityMiddleware::preventTimingAttack();
        
        return $this->success([
            'available' => !$exists,
            'message' => $exists ? '이미 사용중인 이메일입니다.' : '사용 가능한 이메일입니다.'
        ]);
    }
}
