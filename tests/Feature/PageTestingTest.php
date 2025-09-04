<?php

namespace Tests\Feature;

use Tests\TestCase;

class PageTestingTest extends TestCase
{
    /**
     * 모든 페이지가 정상적으로 로드되는지 테스트
     */
    public function test_all_pages_load_successfully()
    {
        $routes = config('routes-web', []);
        $failedRoutes = [];
        
        foreach ($routes as $uri => $viewDirectory) {
            if ($this->shouldSkipRoute($uri)) {
                continue;
            }
            
            try {
                $response = $this->get($uri);
                
                // 200이나 302 상태 코드면 성공
                if (!in_array($response->getStatusCode(), [200, 302, 301])) {
                    // Get more debug information for 500 errors
                    $reason = 'Unexpected status code: ' . $response->getStatusCode();
                    if ($response->getStatusCode() === 500) {
                        $content = $response->getContent();
                        if (str_contains($content, 'ViewException') || str_contains($content, 'View [')) {
                            // Extract view error
                            if (preg_match('/View \[([^\]]+)\] not found/', $content, $matches)) {
                                $reason .= ' (View not found: ' . $matches[1] . ')';
                            } elseif (preg_match('/Unable to locate file in Vite manifest: ([^.]+\.[^.]+)/', $content, $matches)) {
                                $reason .= ' (Vite asset not found: ' . $matches[1] . ')';
                            }
                        }
                        // Extract other error types
                        if (preg_match('/Component \'([^\']+)\' not found/', $content, $matches)) {
                            $reason .= ' (Component not found: ' . $matches[1] . ')';
                        } elseif (preg_match('/Target class \[([^\]]+)\] does not exist/', $content, $matches)) {
                            $reason .= ' (Class not found: ' . $matches[1] . ')';
                        } elseif (preg_match('/Call to undefined function ([^(]+)/', $content, $matches)) {
                            $reason .= ' (Undefined function: ' . $matches[1] . ')';
                        }
                    }
                    
                    $failedRoutes[] = [
                        'uri' => $uri,
                        'status' => $response->getStatusCode(),
                        'reason' => $reason
                    ];
                    continue;
                }
                
                // 200 응답인 경우 기본적인 HTML 구조 확인
                if ($response->getStatusCode() === 200) {
                    $content = $response->getContent();
                    
                    // PHP 에러 체크
                    if ($this->hasPhpErrors($content)) {
                        $failedRoutes[] = [
                            'uri' => $uri,
                            'status' => $response->getStatusCode(),
                            'reason' => 'PHP error detected in response'
                        ];
                        continue;
                    }
                    
                    // 기본 HTML 구조 체크
                    if (!str_contains($content, '<html') || !str_contains($content, '</html>')) {
                        $failedRoutes[] = [
                            'uri' => $uri,
                            'status' => $response->getStatusCode(),
                            'reason' => 'Invalid HTML structure'
                        ];
                        continue;
                    }
                }
                
                echo "\n✅ {$uri} - {$response->getStatusCode()}";
                
            } catch (\Exception $e) {
                $failedRoutes[] = [
                    'uri' => $uri,
                    'status' => 'Exception',
                    'reason' => $e->getMessage()
                ];
                echo "\n❌ {$uri} - Exception: " . substr($e->getMessage(), 0, 100) . '...';
            }
        }
        
        // 결과 출력
        if (!empty($failedRoutes)) {
            echo "\n\n" . str_repeat('=', 50);
            echo "\nFAILED ROUTES:";
            foreach ($failedRoutes as $failed) {
                echo "\n- {$failed['uri']}: {$failed['reason']}";
            }
            echo "\n" . str_repeat('=', 50);
            
            $this->fail(sprintf(
                '%d out of %d routes failed testing',
                count($failedRoutes),
                count($routes)
            ));
        }
        
        echo "\n\n✅ All " . count($routes) . " routes passed successfully!";
        $this->assertTrue(true);
    }
    
    /**
     * 개별 페이지 테스트 (동적 테스트 생성)
     * 
     * @dataProvider routeProvider
     */
    public function test_page_loads_correctly(string $uri, string $viewDirectory)
    {
        if ($this->shouldSkipRoute($uri)) {
            $this->markTestSkipped("Route {$uri} is skipped");
        }
        
        $response = $this->get($uri);
        
        // 상태 코드 검증
        $this->assertContains($response->getStatusCode(), [200, 302, 301], 
            "Route {$uri} returned unexpected status code: {$response->getStatusCode()}"
        );
        
        // 200 응답인 경우 추가 검증
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            
            // PHP 에러 검증
            $this->assertFalse($this->hasPhpErrors($content), 
                "Route {$uri} contains PHP errors"
            );
            
            // HTML 구조 검증
            $this->assertStringContainsString('<html', $content, 
                "Route {$uri} missing HTML opening tag"
            );
            $this->assertStringContainsString('</html>', $content, 
                "Route {$uri} missing HTML closing tag"
            );
        }
    }
    
    /**
     * 라우트 데이터 제공자
     */
    public static function routeProvider(): array
    {
        $routes = config('routes-web', []);
        $data = [];
        
        foreach ($routes as $uri => $viewDirectory) {
            $data["Route: {$uri}"] = [$uri, $viewDirectory];
        }
        
        return $data;
    }
    
    /**
     * 테스트에서 제외할 라우트인지 확인
     */
    protected function shouldSkipRoute(string $uri): bool
    {
        $skipPatterns = [
            '/admin/delete/*',
            '*/logout',
            '/api/*',
        ];
        
        foreach ($skipPatterns as $pattern) {
            if (fnmatch($pattern, $uri)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * PHP 에러가 있는지 확인
     */
    protected function hasPhpErrors(string $content): bool
    {
        $errorPatterns = [
            'Fatal error:',
            'Parse error:',
            'Warning:',
            'Notice:',
            'Exception',
            'Call to undefined',
            'Class not found',
        ];
        
        foreach ($errorPatterns as $pattern) {
            if (stripos($content, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }
}