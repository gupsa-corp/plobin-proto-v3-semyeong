<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PlatformAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 사용자 로그인 확인
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', '로그인이 필요합니다.');
        }

        // 사용자가 platform_admin 역할을 가지고 있는지 확인
        $user = Auth::user();
        
        if (!$user->hasRole('platform_admin')) {
            // 플랫폼 관리자 권한이 없는 경우 403 에러 또는 대시보드로 리다이렉트
            abort(403, '플랫폼 관리자 권한이 필요합니다.');
        }

        return $next($request);
    }
}
