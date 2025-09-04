<?php

namespace App\Http\Traits;

trait HasSecurity
{
    /**
     * 타이밍 공격 방지
     */
    protected function preventTimingAttack(int $minMs = 50, int $maxMs = 150): void
    {
        $delay = rand($minMs * 1000, $maxMs * 1000);
        usleep($delay);
    }

    /**
     * 이메일 정규화
     */
    protected function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    /**
     * 안전한 문자열 비교
     */
    protected function secureCompare(string $known, string $user): bool
    {
        return hash_equals($known, $user);
    }

    /**
     * 민감한 데이터 필터링
     */
    protected function filterSensitiveData(array $data): array
    {
        $sensitive = ['password', 'password_confirmation', 'token', 'secret', 'key', 'private'];
        
        foreach ($sensitive as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[FILTERED]';
            }
        }
        
        return $data;
    }

    /**
     * IP 주소 검증
     */
    protected function isValidIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
}