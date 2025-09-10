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
    $sandboxName = null;
    $sandboxLevel = null; // 'page' 또는 'project' 구분용

    // 우선순위: 페이지 레벨 > 프로젝트 레벨
    if ($page && !empty($page->sandbox_name)) {
        $sandboxName = $page->sandbox_name;
        $sandboxLevel = 'page';
        $hasSandbox = true;
        $hasCustomScreen = !empty($page->custom_screen_settings);
    } elseif ($project && !empty($project->sandbox_name)) {
        $sandboxName = $project->sandbox_name;
        $sandboxLevel = 'project';
        $hasSandbox = true;
        $hasCustomScreen = false; // 프로젝트 레벨에서는 커스텀 화면 설정 없음
    }
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
    @elseif($page && $hasSandbox)
        @if($hasCustomScreen)
            <!-- 기존 페이지 설정 기반 커스텀 화면 로직 (호환성 유지) -->
            @php
                // 커스텀 화면 설정에서 screen_id 가져오기
                $customScreenSettings = $page->custom_screen_settings;
                $screenId = isset($customScreenSettings['screen_id']) ? $customScreenSettings['screen_id'] : null;
            @endphp

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-yellow-800">페이지 설정을 통한 커스텀 화면 (ID: {{ $screenId }})이 설정되어 있지만, 직접 렌더링 모드를 사용중입니다.</p>
            </div>

            <!-- 설정 버튼들을 하단에 표시 -->
            <div class="mt-6 flex justify-center space-x-3">
                <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/custom-screen"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    커스텀 화면 설정
                </a>

                <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/sandbox"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    샌드박스 설정 수정
                </a>
            </div>
        @else
            <!-- 샌드박스만 설정되고 커스텀 화면이 없는 경우 -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                @include('300-page-service.308-page-project-dashboard.231-sandbox-content')
            </div>
        @endif
    @else
        <!-- 빈 페이지 안내 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            @include('300-page-service.308-page-project-dashboard.241-empty-page-content')
        </div>
    @endif
</div>

<!-- JavaScript 에러 처리 -->
@include('300-page-service.308-page-project-dashboard.400-javascript-error-handling')
