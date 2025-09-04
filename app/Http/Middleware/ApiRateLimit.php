<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\ApiException;

class ApiRateLimit
{
    /**
     * API 요청 횟수 제한 미들웨어
     *
     * @param Request $request
     * @param Closure $next
     * @param int $maxAttempts 최대 시도 횟수
     * @param int $decayMinutes 제한 시간(분)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1)
    {
        $key = $this->getRateLimitKey($request);
        $attempts = Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            throw ApiException::tooManyRequests(
                "요청 제한에 도달했습니다. {$decayMinutes}분 후 다시 시도해주세요."
            );
        }

        Cache::put($key, $attempts + 1, now()->addMinutes($decayMinutes));

        $response = $next($request);

        // 응답에 Rate Limit 헤더 추가
        $remaining = max(0, $maxAttempts - $attempts - 1);
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', $remaining);
        $response->headers->set('X-RateLimit-Reset', now()->addMinutes($decayMinutes)->timestamp);

        return $response;
    }

    /**
     * Rate Limit 키 생성
     */
    private function getRateLimitKey(Request $request): string
    {
        $ip = $request->ip();
        $route = $request->route()->getName() ?? $request->getPathInfo();
        
        return "rate_limit:{$ip}:{$route}";
    }
}