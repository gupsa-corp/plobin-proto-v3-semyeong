<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Event;
use App\Http\CoreApi\QueryLogger;

class QueryLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // 쿼리 실행 이벤트 리스너 등록
        Event::listen(QueryExecuted::class, function (QueryExecuted $query) {
            // 마이그레이션이나 시딩 중이면 로깅 건너뛰기
            if (app()->runningInConsole() && (
                request()->server('argv.1') === 'migrate' || 
                request()->server('argv.1') === 'db:seed'
            )) {
                return;
            }
            
            // query_logs 테이블 자체에 대한 쿼리는 로깅하지 않음 (무한 루프 방지)
            if (str_contains($query->sql, 'query_logs')) {
                return;
            }
            
            // 개발 환경에서만 로깅 (필요에 따라 설정 변경 가능)
            if (config('app.debug', false) && config('database.query_logging', true)) {
                QueryLogger::log(
                    $query->sql,
                    $query->bindings,
                    $query->time, // Laravel은 이미 밀리초로 제공
                    $query->connectionName
                );
            }
        });
    }
}