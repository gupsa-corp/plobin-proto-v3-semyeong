<?php

namespace App\Http\CoreApi\QueryLog\GetStats;

use App\Http\Controller;
use App\Http\CoreApi\QueryLogger;

class Controller extends \App\Http\Controller
{
    public function __invoke()
    {
        $stats = QueryLogger::getStats();
        
        return response()->json([
            'stats' => $stats
        ]);
    }
}