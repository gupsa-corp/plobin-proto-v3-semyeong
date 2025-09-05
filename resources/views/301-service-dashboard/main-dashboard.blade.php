{{-- 메인 대시보드 화면 --}}
<div id="mainDashboardScreen" style="display: none;">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">대시보드</h1>
        <p class="text-lg text-gray-500" id="orgNameDisplay">선택한 조직의 대시보드입니다</p>
    </div>

    {{-- 프로젝트 섹션 --}}
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-800">프로젝트</h2>
            <button id="createProjectBtn" class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                새 프로젝트
            </button>
        </div>

        <div id="projectsList" class="bg-white rounded-lg shadow p-6">
            {{-- 프로젝트가 로드될 공간 --}}
            <div class="text-center py-8" id="emptyProjectsState">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">프로젝트가 없습니다</h3>
                <p class="mt-2 text-gray-500">새 프로젝트를 생성하여 시작하세요</p>
                <button class="mt-4 bg-teal-500 hover:bg-teal-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    첫 번째 프로젝트 만들기
                </button>
            </div>
        </div>
    </div>

    {{-- 빠른 시작 가이드 --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $dashboardItems = include(resource_path('views/301-service-dashboard/dashboard-data.blade.php'));
        @endphp

        @foreach($dashboardItems as $item)
            <a href="{{ $item['url'] }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow duration-200 cursor-pointer {{ $item['active'] ? 'ring-2 ring-teal-500' : '' }}">
                <div class="flex items-center mb-4">
                    <div class="bg-teal-100 p-3 rounded-lg">
                        @if($item['icon'] === 'project')
                            <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        @elseif($item['icon'] === 'analytics')
                            <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        @elseif($item['icon'] === 'settings')
                            <svg class="h-6 w-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">{{ $item['title'] }}</h3>
                </div>
                <p class="text-gray-600 mb-4">{{ $item['description'] }}</p>
                <span class="text-teal-600 hover:text-teal-700 text-sm font-medium">시작하기 →</span>
            </a>
        @endforeach
    </div>
</div>
