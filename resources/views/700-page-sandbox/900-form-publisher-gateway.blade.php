@extends('300-common.100-layout')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 2rem;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 1rem 2rem; border-radius: 10px; margin-bottom: 2rem;">
            <h1 style="color: white; font-size: 1.5rem; margin: 0;">🎨 Form Publisher</h1>
            <p style="color: rgba(255, 255, 255, 0.8); margin: 0.5rem 0 0 0;">샌드박스 폼 생성 및 관리 도구</p>
        </div>
        
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
            @php
                // 샌드박스 경로 설정
                $sandboxPath = storage_path('storage-sandbox-1/Frontend/Page');
                $page = $page ?? 'list';
                
                // 페이지별 파일 매핑
                $pageFiles = [
                    'editor' => 'form-publisher-editor.php',
                    'preview' => 'form-publisher-preview.php', 
                    'list' => 'form-publisher-list.php'
                ];
                
                $currentFile = $pageFiles[$page] ?? $pageFiles['list'];
                $filePath = $sandboxPath . '/' . $currentFile;
                
                // GET 파라미터 전달
                $queryParams = [];
                if (isset($id)) {
                    $queryParams['id'] = $id;
                }
                if (request()->has('edit')) {
                    $queryParams['edit'] = request('edit');
                }
                if (request()->has('search')) {
                    $queryParams['search'] = request('search');
                }
                if (request()->has('page')) {
                    $queryParams['page'] = request('page');
                }
                
                // 쿼리 스트링 생성
                $queryString = '';
                if (!empty($queryParams)) {
                    $queryString = '?' . http_build_query($queryParams);
                }
                
                // POST 데이터 전달
                if (request()->isMethod('post')) {
                    $_POST = request()->all();
                }
                
                // GET 파라미터를 $_GET에 설정
                foreach ($queryParams as $key => $value) {
                    $_GET[$key] = $value;
                }
            @endphp
            
            @if (file_exists($filePath))
                @php
                    // 출력 버퍼링 시작
                    ob_start();
                    
                    // 현재 디렉토리 변경
                    $originalDir = getcwd();
                    chdir(dirname($filePath));
                    
                    try {
                        // PHP 파일 실행
                        include $filePath;
                    } catch (Exception $e) {
                        echo '<div style="color: red; padding: 1rem; background: #f8d7da; border-radius: 5px;">';
                        echo '<h3>오류가 발생했습니다</h3>';
                        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
                        echo '</div>';
                    } finally {
                        // 원래 디렉토리로 복원
                        chdir($originalDir);
                    }
                    
                    // 출력 내용 가져오기
                    $content = ob_get_clean();
                    
                    // HTML 문서인 경우 body 내용만 추출
                    if (strpos($content, '<!DOCTYPE html') !== false) {
                        preg_match('/<body[^>]*>(.*?)<\/body>/s', $content, $matches);
                        if (!empty($matches[1])) {
                            echo $matches[1];
                        } else {
                            echo $content;
                        }
                    } else {
                        echo $content;
                    }
                @endphp
            @else
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <h2>❌ 페이지를 찾을 수 없습니다</h2>
                    <p>요청하신 Form Publisher 페이지가 존재하지 않습니다.</p>
                    <p style="font-size: 0.9rem; margin-top: 1rem; color: #999;">
                        파일 경로: {{ $filePath }}
                    </p>
                    <div style="margin-top: 2rem;">
                        <a href="{{ route('sandbox.form-publisher.list') }}" 
                           style="background: #667eea; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 5px; display: inline-block;">
                            📋 폼 목록으로 이동
                        </a>
                    </div>
                </div>
            @endif
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ url('/sandbox') }}" 
               style="color: white; text-decoration: none; background: rgba(255, 255, 255, 0.2); padding: 0.5rem 1rem; border-radius: 5px; backdrop-filter: blur(10px);">
                🏠 샌드박스 홈으로 돌아가기
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
/* 샌드박스 내용과 충돌하지 않도록 스타일 격리 */
.sandbox-content {
    all: initial;
}

/* 필요한 경우 추가 스타일 오버라이드 */
.sandbox-content * {
    box-sizing: border-box;
}
</style>
@endsection