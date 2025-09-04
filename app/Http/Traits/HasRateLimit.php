<?php

namespace App\Http\Traits;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

trait HasRateLimit
{
    /**
     * Rate limit 체크
     */
    protected function checkRateLimit(Request $request, string $key = null, int $maxAttempts = 10, int $decayMinutes = 1): void
    {
        $rateLimitKey = $key ?: $this->getRateLimitKey($request);
        
        if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            throw ApiException::tooManyRequests(
                "너무 많은 요청입니다. {$seconds}초 후 다시 시도해주세요."
            );
        }

        RateLimiter::hit($rateLimitKey, $decayMinutes * 60);
    }

    /**
     * Rate limit 키 생성
     */
    protected function getRateLimitKey(Request $request): string
    {
        $action = class_basename(static::class);
        return strtolower($action) . ':' . $request->ip();
    }
}