<?php

namespace App\Http\CoreApi\QueryLog\GetLogs;

use App\Http\Controller;
use App\Http\CoreApi\QueryLogger;

class Controller extends \App\Http\Controller
{
    public function __invoke()
    {
        $filters = [
            'user_id' => request('user_id'),
            'min_execution_time' => request('min_execution_time'),
            'date_from' => request('date_from'),
            'date_to' => request('date_to'),
            'query_like' => request('query_like'),
        ];
        
        // null 값 제거
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });
        
        $limit = request('limit', 100);
        $limit = min($limit, 1000); // 최대 1000개로 제한
        
        $logs = QueryLogger::getLogs($limit, $filters);
        $stats = QueryLogger::getStats();
        
        return response()->json([
            'logs' => $logs,
            'stats' => $stats,
            'filters_applied' => $filters,
            'limit' => $limit
        ]);
    }
}