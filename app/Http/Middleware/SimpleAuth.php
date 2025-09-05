<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;

/**
 * 단순한 인증 미들웨어
 *
 * 두 가지만 지원:
 * 1. 웹 세션 인증 (브라우저)
 * 2. API 토큰 인증 (API)
 */
class SimpleAuth
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('SimpleAuth called', [
            'has_auth_header' => $request->hasHeader('Authorization'),
            'auth_header' => $request->header('Authorization') ? 'Bearer ***' : 'none',
            'expects_json' => $request->expectsJson(),
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept')
        ]);
        
        if ($this->hasApiToken($request)) {
            return $this->authenticateWithToken($request, $next);
        }

        if (auth()->check()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            throw ApiException::unauthorized('인증이 필요합니다.');
        }

        return redirect()->route('login');
    }

    private function hasApiToken(Request $request): bool
    {
        return $request->hasHeader('Authorization') &&
               str_starts_with($request->header('Authorization'), 'Bearer ');
    }

    private function authenticateWithToken(Request $request, Closure $next)
    {
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        \Log::info('Token auth attempt', ['token_prefix' => substr($token, 0, 10) . '...']);

        $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        \Log::info('Token lookup result', ['found' => $accessToken ? 'yes' : 'no']);
        
        if (!$accessToken) {
            \Log::warning('Token not found in database');
            throw ApiException::unauthorized('유효하지 않은 토큰입니다.');
        }

        $user = $accessToken->tokenable;
        if (!$user) {
            throw ApiException::unauthorized('사용자를 찾을 수 없습니다.');
        }

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        // Laravel의 auth 시스템에도 사용자를 설정
        auth()->setUser($user);

        return $next($request);
    }
}
