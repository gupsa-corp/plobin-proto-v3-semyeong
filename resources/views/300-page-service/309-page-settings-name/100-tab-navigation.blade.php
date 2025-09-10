<!-- 페이지 설정 탭 네비게이션 -->
<div class="mb-6">
    <!-- 헤더 -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">페이지 설정</h1>
        <p class="text-gray-500 text-sm mt-1">페이지의 설정을 변경하고 관리할 수 있습니다.</p>
    </div>

    <!-- 탭 네비게이션 -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('project.dashboard.page.settings.name', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}" 
               class="py-2 px-1 border-b-2 {{ request()->routeIs('project.dashboard.page.settings.name') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                페이지 이름 변경
            </a>
            <a href="{{ route('project.dashboard.page.settings.custom-screen', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}" 
               class="py-2 px-1 border-b-2 {{ request()->routeIs('project.dashboard.page.settings.custom-screen') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                커스텀 화면 선택
            </a>
            <a href="{{ route('project.dashboard.page.settings.deployment', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}" 
               class="py-2 px-1 border-b-2 {{ request()->routeIs('project.dashboard.page.settings.deployment') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                배포 상태
            </a>
            <a href="{{ route('project.dashboard.page.settings.permissions', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}" 
               class="py-2 px-1 border-b-2 {{ request()->routeIs('project.dashboard.page.settings.permissions') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                보기 권한
            </a>
            <a href="{{ route('project.dashboard.page.settings.history', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}" 
               class="py-2 px-1 border-b-2 {{ request()->routeIs('project.dashboard.page.settings.history') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                변경 이력
            </a>
            <a href="{{ route('project.dashboard.page.settings.delete', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}" 
               class="py-2 px-1 border-b-2 {{ request()->routeIs('project.dashboard.page.settings.delete') ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-red-700 hover:border-red-300' }} font-medium text-sm">
                페이지 삭제
            </a>
        </nav>
    </div>
</div>