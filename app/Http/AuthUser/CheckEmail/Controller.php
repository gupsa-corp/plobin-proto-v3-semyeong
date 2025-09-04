<?php

namespace App\Http\AuthUser\CheckEmail;

use App\Http\Controllers\Controller as BaseController;
use App\Http\AuthUser\CheckEmail\Request as CheckEmailRequest;
use App\Exceptions\ApiException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Exception;

class Controller extends BaseController
{
    public function __invoke(CheckEmailRequest $request)
    {
        try {
            $this->checkRateLimit($request);
            
            $normalizedEmail = $this->normalizeEmail($request->email);
            $exists = $this->checkEmailExists($normalizedEmail);
            
            $this->preventTimingAttack();

            return $this->successResponse($exists);
            
        } catch (ApiException $e) {
            return $e->render();
        } catch (Exception $e) {
            $this->logError($e, $request);
            throw ApiException::serverError('서버 오류가 발생했습니다. 잠시 후 다시 시도해주세요.');
        }
    }

    private function checkRateLimit(CheckEmailRequest $request): void
    {
        $key = 'check-email:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            throw ApiException::tooManyRequests(
                "너무 많은 요청입니다. {$seconds}초 후 다시 시도해주세요."
            );
        }

        RateLimiter::hit($key, 60);
    }

    private function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    private function checkEmailExists(string $normalizedEmail): bool
    {
        $cacheKey = 'email_check:' . hash('sha256', $normalizedEmail);
        
        return Cache::remember($cacheKey, 300, function () use ($normalizedEmail) {
            return User::whereRaw('LOWER(email) = ?', [$normalizedEmail])->exists();
        });
    }

    private function successResponse(bool $exists): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'available' => !$exists,
            'message' => $exists ? '이미 사용중인 이메일입니다.' : '사용 가능한 이메일입니다.'
        ]);
    }

    private function logError(Exception $e, CheckEmailRequest $request): void
    {
        Log::error('CheckEmail error: ' . $e->getMessage(), [
            'email' => $request->email ?? 'unknown',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    /**
     * 타이밍 공격 방지를 위한 일정한 응답 시간 보장
     */
    private function preventTimingAttack(): void
    {
        // 50-150ms 사이의 랜덤한 지연
        $delay = rand(50000, 150000);
        usleep($delay);
    }
}
