<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 인증 실패시 리디렉션 설정
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            // API 요청인 경우 null 반환하여 리디렉션 방지
            if ($request->expectsJson() || str_starts_with($request->getPathInfo(), '/api/')) {
                return null;
            }
            // 웹 요청인 경우만 login 페이지로 리디렉션
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 인증 실패 처리 (가장 먼저 처리)
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson() || str_starts_with($request->getPathInfo(), '/api/')) {
                return response()->json([
                    'success' => false,
                    'message' => '인증이 필요합니다.'
                ], 401);
            }
        });
        
        $exceptions->render(function (\App\Exceptions\ApiException $e) {
            return $e->render();
        });
        
        $exceptions->render(function (\Exception $e, \Illuminate\Http\Request $request) {
            // API 라우트에서만 JSON 응답
            if ($request->expectsJson() || str_starts_with($request->getPathInfo(), '/api/')) {
                \Log::error('API Error: ' . $e->getMessage(), [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'params' => $request->except(['password', 'password_confirmation', 'token'])
                ]);
                
                return \App\Exceptions\ApiException::serverError()->render();
            }
        });
    })->create();
