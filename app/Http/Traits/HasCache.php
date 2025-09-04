<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Cache;

trait HasCache
{
    /**
     * 캐시 데이터 조회/저장
     */
    protected function cacheRemember(string $key, int $ttl, callable $callback): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * 안전한 캐시 키 생성
     */
    protected function makeCacheKey(string $prefix, ...$params): string
    {
        $key = $prefix;
        
        foreach ($params as $param) {
            if (is_array($param) || is_object($param)) {
                $key .= ':' . md5(serialize($param));
            } else {
                $key .= ':' . $param;
            }
        }
        
        return $key;
    }

    /**
     * 캐시 무효화
     */
    protected function forgetCache(string $key): void
    {
        Cache::forget($key);
    }

    /**
     * 패턴별 캐시 무효화
     */
    protected function flushCacheByPattern(string $pattern): void
    {
        // Redis 사용 시
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            $redis = Cache::getStore()->connection();
            $keys = $redis->keys($pattern);
            if (!empty($keys)) {
                $redis->del($keys);
            }
        }
    }
}