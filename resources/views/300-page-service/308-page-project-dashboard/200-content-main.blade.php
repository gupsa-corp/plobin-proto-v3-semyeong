@php
    // 컨트롤러에서 전달받은 변수들 사용
    $organization = $organization ?? null;
    $project = $project ?? null;
    $page = $page ?? null;
    $customScreen = $customScreen ?? null;
    $sandboxInfo = $sandboxInfo ?? null;
    
    $organizationId = $organization ? $organization->id : null;
    $projectId = $project ? $project->id : null;
    $pageId = $page ? $page->id : null;

    // 샌드박스 정보는 컨트롤러에서 처리된 데이터 사용
    $hasSandbox = $sandboxInfo['has_sandbox'] ?? false;
    $hasCustomScreen = $sandboxInfo['has_custom_screen'] ?? false;
    $sandboxName = $sandboxInfo['sandbox_name'] ?? null;
    $sandboxLevel = $sandboxInfo['sandbox_level'] ?? null;
    $customScreenFolder = $sandboxInfo['custom_screen_folder'] ?? null;
@endphp

<!-- 페이지별 커스텀 콘텐츠 -->
<div class="px-6 py-6" x-data="">
    @if(!$organization)
        <!-- 조직이 없는 경우 -->
        @include('300-page-service.308-page-project-dashboard.210-error-organization-not-found')
    @elseif(!$project)
        <!-- 프로젝트가 없는 경우 -->
        @include('300-page-service.308-page-project-dashboard.211-error-project-not-found')
    @elseif(isset($customScreen) && !empty($customScreen))
        <!-- 커스텀 화면이 있는 경우 렌더링 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            @include('300-page-service.308-page-project-dashboard.221-custom-screen-content')
        </div>
    @elseif($page && $hasCustomScreen && $customScreenFolder)
        <!-- sandbox_custom_screen_folder 기반 커스텀 화면 렌더링 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            @php
                // storage/sandbox 경로에서 실제 파일 찾기
                $customScreenFilePath = storage_path('sandbox/' . $sandboxName . '/frontend/' . trim($customScreenFolder, '/') . '/000-content.blade.php');
            @endphp

            @if(file_exists($customScreenFilePath))
                {!! view()->file($customScreenFilePath, get_defined_vars())->render() !!}
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-800">
                                커스텀 화면 파일을 찾을 수 없습니다: <code>{{ $customScreenFilePath }}</code>
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @elseif($page && $hasSandbox)
        <!-- 일반 샌드박스만 설정된 경우 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            @include('300-page-service.308-page-project-dashboard.231-sandbox-content')
        </div>
    @else
        <!-- 빈 페이지 안내 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            @include('300-page-service.308-page-project-dashboard.241-empty-page-content')
        </div>
    @endif
</div>

<!-- JavaScript 에러 처리 -->
@include('300-page-service.308-page-project-dashboard.400-javascript-error-handling')
