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
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
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
