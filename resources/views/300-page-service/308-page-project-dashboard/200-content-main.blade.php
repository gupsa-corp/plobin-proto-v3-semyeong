@php
    // 라우트에서 전달받은 변수들 사용
    // $organization, $project, $page 변수들이 라우트에서 전달됨

    // 안전한 변수 할당 (변수가 정의되지 않은 경우 null로 처리)
    $organization = $organization ?? null;
    $project = $project ?? null;
    $page = $page ?? null;
    $customScreen = $customScreen ?? null;

    $organizationId = $organization ? $organization->id : null;
    $projectId = $project ? $project->id : null;
    $pageId = $page ? $page->id : null;

    // 프로젝트 레벨 또는 페이지 레벨에서 샌드박스 설정 확인
    $hasSandbox = false;
    $hasCustomScreen = false;
    $sandboxType = null;
    $sandboxLevel = null; // 'page' 또는 'project' 구분용

    // 우선순위: 페이지 레벨 > 프로젝트 레벨
    if ($page && !empty($page->sandbox_type)) {
        $sandboxType = $page->sandbox_type;
        $sandboxLevel = 'page';
        $hasSandbox = true;
        $hasCustomScreen = !empty($page->custom_screen_settings);
    } elseif ($project && !empty($project->sandbox_type)) {
        $sandboxType = $project->sandbox_type;
        $sandboxLevel = 'project';
        $hasSandbox = true;
        $hasCustomScreen = false; // 프로젝트 레벨에서는 커스텀 화면 설정 없음
    }
@endphp

<!-- 페이지별 커스텀 콘텐츠 -->
<div class="px-6 py-6" x-data="">
    내용 / 드롭다운 /
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
