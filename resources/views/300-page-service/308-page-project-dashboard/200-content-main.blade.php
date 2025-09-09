@php
    // URL에서 organization ID, project ID 추출
    $urlSegments = explode('/', request()->path());
    $organizationId = isset($urlSegments[1]) ? $urlSegments[1] : null;
    $projectId = isset($urlSegments[3]) ? $urlSegments[3] : null;
    
    // 조직 정보 가져오기
    $organization = null;
    $project = null;
    $firstPage = null;
    
    try {
        if ($organizationId && is_numeric($organizationId)) {
            $organization = \App\Models\Organization::find($organizationId);
        }
        
        if ($projectId && is_numeric($projectId)) {
            $project = \App\Models\Project::find($projectId);
            
            // 첫 번째 페이지 찾기
            if ($project) {
                $firstPage = \App\Models\ProjectPage::where('project_id', $projectId)
                    ->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->first();
            }
        }
    } catch (\Exception $e) {
        // 데이터베이스 오류 등이 발생해도 계속 진행
        $organization = null;
        $project = null;
        $firstPage = null;
    }
@endphp

<!-- 페이지별 커스텀 콘텐츠 -->
<div class="px-6 py-6" x-data="">
    @if(!$organization)
        <!-- 조직이 없는 경우 -->
        <div class="flex flex-col items-center justify-center min-h-96 bg-white rounded-lg border border-red-200 shadow-sm">
            <div class="text-center max-w-md">
                <div class="mb-6">
                    <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                
                <h2 class="text-xl font-semibold text-red-800 mb-2">
                    조직을 찾을 수 없습니다
                </h2>
                
                <p class="text-red-600 mb-6">
                    요청한 조직이 존재하지 않거나 접근 권한이 없습니다.
                </p>
                
                <div class="space-y-3">
                    <a href="/organizations" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-6m-2-5.5v-.5m0 0V15a2 2 0 011.5-1.943L15 13V9a2 2 0 012-2h1a2 2 0 012 2v4l-1.943 1.5A2 2 0 0119 15v.5m0 0v.5M13 21h6"/>
                        </svg>
                        조직 목록으로 이동
                    </a>
                </div>
            </div>
        </div>
    @elseif(!$project)
        <!-- 프로젝트가 없는 경우 -->
        <div class="flex flex-col items-center justify-center min-h-96 bg-white rounded-lg border border-yellow-200 shadow-sm">
            <div class="text-center max-w-md">
                <div class="mb-6">
                    <svg class="w-16 h-16 text-yellow-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                
                <h2 class="text-xl font-semibold text-yellow-800 mb-2">
                    프로젝트를 찾을 수 없습니다
                </h2>
                
                <p class="text-yellow-600 mb-6">
                    요청한 프로젝트가 존재하지 않거나 접근 권한이 없습니다.
                </p>
                
                <div class="space-y-3">
                    <a href="/organizations/{{ $organizationId }}/projects" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-6m-2-5.5v-.5m0 0V15a2 2 0 011.5-1.943L15 13V9a2 2 0 012-2h1a2 2 0 012 2v4l-1.943 1.5A2 2 0 0119 15v.5m0 0v.5M13 21h6"/>
                        </svg>
                        프로젝트 목록으로 이동
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- 빈 페이지 안내 -->
        <div class="flex flex-col items-center justify-center min-h-96 bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="text-center max-w-md">
                <div class="mb-6">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                
                <h2 class="text-xl font-semibold text-gray-900 mb-2">
                    페이지 콘텐츠가 없습니다
                </h2>
                
                <p class="text-gray-500 mb-6">
                    이 페이지는 아직 설정되지 않았습니다.<br>
                    페이지 설정에서 샌드박스 설정 또는 커스텀 화면을 구성해주세요.
                </p>
                
                <div class="space-y-3">
                    @if($firstPage)
                        <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $firstPage->id }}/settings/custom-screen" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            커스텀 화면 설정하기
                        </a>
                        
                        <div class="text-sm text-gray-400">
                            또는 샌드박스 설정에서 바로 시작하세요
                        </div>
                        
                        <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $firstPage->id }}/settings/sandbox" 
                           class="inline-flex items-center justify-center px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            샌드박스 설정하기
                        </a>
                    @else
                        <div class="text-sm text-gray-500 mb-4">
                            프로젝트에 페이지가 없습니다. 먼저 페이지를 생성해주세요.
                        </div>
                        
                        <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            페이지 생성하기
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Livewire 에러 처리 JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Livewire 에러 리스너
    window.addEventListener('livewire:error', function(e) {
        console.error('Livewire Error:', e.detail);
        
        // 사용자에게 친화적인 에러 메시지 표시
        const errorContainer = document.createElement('div');
        errorContainer.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg z-50';
        errorContainer.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <span>페이지 로딩 중 문제가 발생했습니다. 페이지를 새로고침해주세요.</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(errorContainer);
        
        // 5초 후 자동 제거
        setTimeout(function() {
            if (errorContainer.parentNode) {
                errorContainer.parentNode.removeChild(errorContainer);
            }
        }, 5000);
    });
    
    // 일반 JavaScript 에러 처리
    window.addEventListener('error', function(e) {
        if (e.message.includes('Livewire') || e.message.includes('Component not found')) {
            console.error('Livewire Component Error:', e.message);
            
            // 컴포넌트를 찾을 수 없는 경우 페이지 새로고침 제안
            if (e.message.includes('Component not found')) {
                const refreshContainer = document.createElement('div');
                refreshContainer.className = 'fixed bottom-4 right-4 bg-yellow-500 text-white px-4 py-3 rounded-lg shadow-lg z-50';
                refreshContainer.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>컴포넌트를 찾을 수 없습니다.</span>
                        <button onclick="location.reload()" class="ml-2 bg-white text-yellow-500 px-2 py-1 rounded text-sm hover:bg-gray-100">
                            새로고침
                        </button>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                `;
                
                document.body.appendChild(refreshContainer);
                
                // 10초 후 자동 제거
                setTimeout(function() {
                    if (refreshContainer.parentNode) {
                        refreshContainer.parentNode.removeChild(refreshContainer);
                    }
                }, 10000);
            }
        }
    });
    
    // 페이지 로드 시 Livewire 상태 확인
    setTimeout(function() {
        if (typeof window.Livewire !== 'undefined') {
            console.log('Livewire loaded successfully');
        } else {
            console.warn('Livewire not loaded - some features may not work');
        }
    }, 1000);
});
</script>
