<!-- 프로젝트 설정 탭 네비게이션 -->
<div class="mb-6">
    <!-- 헤더 -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">프로젝트 설정</h1>
        <p class="text-gray-500 text-sm mt-1">프로젝트의 설정을 변경하고 관리할 수 있습니다.</p>
    </div>

    <!-- 탭 네비게이션 -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('project.dashboard.project.settings.name', ['id' => request()->route('id'), 'projectId' => request()->route('projectId')]) }}" 
               class="py-2 px-1 border-b-2 {{ request()->routeIs('project.dashboard.project.settings.name') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                프로젝트 이름 변경
            </a>
            <a href="{{ route('project.dashboard.project.settings.sandbox', ['id' => request()->route('id'), 'projectId' => request()->route('projectId')]) }}" 
               class="py-2 px-1 border-b-2 {{ request()->routeIs('project.dashboard.project.settings.sandbox') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                샌드박스 설정
            </a>
            <a href="#" class="py-2 px-1 border-b-2 border-transparent text-gray-400 font-medium text-sm cursor-not-allowed">
                사용자 관리 (준비중)
            </a>
            <a href="#" class="py-2 px-1 border-b-2 border-transparent text-gray-400 font-medium text-sm cursor-not-allowed">
                권한 관리 (준비중)
            </a>
            <a href="#" class="py-2 px-1 border-b-2 border-transparent text-red-400 font-medium text-sm cursor-not-allowed">
                프로젝트 삭제 (준비중)
            </a>
        </nav>
    </div>
</div>