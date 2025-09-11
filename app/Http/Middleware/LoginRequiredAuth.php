<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginRequiredAuth
{
    public function handle(Request $request, Closure $next)
    {
        // 인증 체크 활성화 여부 확인 (기본값: true)
        $authCheckEnabled = config('dashboard.auth_check_enabled', true);
        
        // 인증 체크가 비활성화된 경우 통과
        if (!$authCheckEnabled) {
            return $next($request);
        }
        
        // 인증되지 않은 경우 로그인 페이지로 리디렉트
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        return $next($request);
    }
}