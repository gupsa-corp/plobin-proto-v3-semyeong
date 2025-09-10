<?php

namespace App\Http\CoreApi\QueryLog\Cleanup;

use App\Http\Controller;
use App\Http\CoreApi\QueryLogger;

class Controller extends \App\Http\Controller
{
    public function __invoke()
    {
        $days = request('days', 30);
        $days = max(1, min($days, 365)); // 1일에서 365일 사이로 제한
        
        $deletedCount = QueryLogger::cleanup($days);
        
        return response()->json([
            'deleted_count' => $deletedCount,
            'days' => $days,
            'message' => "{$days}일 이전 쿼리 로그 {$deletedCount}개가 삭제되었습니다."
        ]);
    }
}