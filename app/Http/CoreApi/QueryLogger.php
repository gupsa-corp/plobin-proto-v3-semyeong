<?php

namespace App\Http\CoreApi;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class QueryLogger
{
    private static $enabled = true;
    private static $startTime;
    private static $currentQuery;
    private static $currentBindings;
    
    /**
     * 쿼리 로깅 활성화/비활성화
     */
    public static function enable($enabled = true)
    {
        self::$enabled = $enabled;
    }
    
    /**
     * 쿼리 실행 전 호출
     */
    public static function startQuery($query, $bindings = [])
    {
        if (!self::$enabled) {
            return;
        }
        
        self::$startTime = microtime(true);
        self::$currentQuery = $query;
        self::$currentBindings = $bindings;
    }
    
    /**
     * 쿼리 실행 후 호출
     */
    public static function endQuery($connection = null)
    {
        if (!self::$enabled || !self::$startTime) {
            return;
        }
        
        $executionTime = (microtime(true) - self::$startTime) * 1000; // 밀리초로 변환
        
        // 스택 트레이스 분석
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
        $caller = self::findRelevantCaller($trace);
        
        // 로그 데이터 준비
        $logData = [
            'query' => self::$currentQuery,
            'bindings' => !empty(self::$currentBindings) ? json_encode(self::$currentBindings) : null,
            'execution_time' => $executionTime,
            'connection' => $connection ?: config('database.default'),
            'file' => $caller['file'] ?? null,
            'line' => $caller['line'] ?? null,
            'stack_trace' => json_encode(array_slice($trace, 0, 5)), // 상위 5개 스택만 저장
            'user_id' => Auth::id(),
            'session_id' => session()->getId(),
            'ip_address' => Request::ip(),
            'request_method' => Request::method(),
            'request_url' => Request::fullUrl(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        // 비동기로 로그 저장 (성능 최적화)
        self::saveLogAsync($logData);
        
        // 초기화
        self::$startTime = null;
        self::$currentQuery = null;
        self::$currentBindings = null;
    }
    
    /**
     * 직접 쿼리 로그 저장
     */
    public static function log($query, $bindings = [], $executionTime = 0, $connection = null)
    {
        if (!self::$enabled) {
            return;
        }
        
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
        $caller = self::findRelevantCaller($trace);
        
        $logData = [
            'query' => $query,
            'bindings' => !empty($bindings) ? json_encode($bindings) : null,
            'execution_time' => $executionTime,
            'connection' => $connection ?: config('database.default'),
            'file' => $caller['file'] ?? null,
            'line' => $caller['line'] ?? null,
            'stack_trace' => json_encode(array_slice($trace, 0, 5)),
            'user_id' => Auth::id(),
            'session_id' => session()->getId(),
            'ip_address' => Request::ip(),
            'request_method' => Request::method(),
            'request_url' => Request::fullUrl(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        self::saveLogAsync($logData);
    }
    
    /**
     * 관련 있는 호출자 찾기 (프레임워크 코드 제외)
     */
    private static function findRelevantCaller($trace)
    {
        $excludePaths = [
            'vendor/',
            'bootstrap/',
            'QueryLogger.php',
            'DatabaseService.php'
        ];
        
        foreach ($trace as $frame) {
            if (!isset($frame['file'])) {
                continue;
            }
            
            $file = $frame['file'];
            $isExcluded = false;
            
            foreach ($excludePaths as $excludePath) {
                if (strpos($file, $excludePath) !== false) {
                    $isExcluded = true;
                    break;
                }
            }
            
            if (!$isExcluded) {
                return $frame;
            }
        }
        
        return $trace[0] ?? [];
    }
    
    /**
     * 비동기로 로그 저장 (성능 최적화)
     */
    private static function saveLogAsync($logData)
    {
        try {
            // 무한 루프 방지를 위해 항상 동기 처리
            DB::table('query_logs')->insert($logData);
        } catch (\Exception $e) {
            // 로그 저장 실패 시 에러 로그만 남기고 원래 작업은 계속 진행
            \Log::error('Query log save failed: ' . $e->getMessage());
        }
    }
    
    /**
     * 쿼리 로그 조회
     */
    public static function getLogs($limit = 100, $filters = [])
    {
        $query = DB::table('query_logs')->orderBy('created_at', 'desc');
        
        // 필터 적용
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }
        
        if (isset($filters['min_execution_time'])) {
            $query->where('execution_time', '>=', $filters['min_execution_time']);
        }
        
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        
        if (isset($filters['query_like'])) {
            $query->where('query', 'like', '%' . $filters['query_like'] . '%');
        }
        
        return $query->limit($limit)->get();
    }
    
    /**
     * 성능 통계 조회
     */
    public static function getStats()
    {
        $stats = DB::table('query_logs')
            ->selectRaw('
                COUNT(*) as total_queries,
                AVG(execution_time) as avg_execution_time,
                MAX(execution_time) as max_execution_time,
                MIN(execution_time) as min_execution_time,
                COUNT(CASE WHEN execution_time > 1000 THEN 1 END) as slow_queries
            ')
            ->first();
            
        return [
            'total_queries' => $stats->total_queries ?? 0,
            'avg_execution_time' => round($stats->avg_execution_time ?? 0, 2),
            'max_execution_time' => round($stats->max_execution_time ?? 0, 2),
            'min_execution_time' => round($stats->min_execution_time ?? 0, 2),
            'slow_queries' => $stats->slow_queries ?? 0,
            'slow_query_percentage' => $stats->total_queries > 0 ? round(($stats->slow_queries / $stats->total_queries) * 100, 2) : 0
        ];
    }
    
    /**
     * 오래된 로그 정리
     */
    public static function cleanup($days = 30)
    {
        $cutoffDate = now()->subDays($days);
        return DB::table('query_logs')->where('created_at', '<', $cutoffDate)->delete();
    }
}