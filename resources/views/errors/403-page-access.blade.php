@include('000-common-layouts.001-html-lang')
@include('300-page-service.300-common.301-layout-head', ['title' => '접근 권한 없음'])
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-6">
            <!-- 아이콘 -->
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>

            <!-- 제목 -->
            <div class="text-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900">접근 권한이 없습니다</h1>
                <p class="text-gray-600 mt-2">
                    {{ $message ?? '이 페이지에 접근할 권한이 없습니다.' }}
                </p>
            </div>

            <!-- 프로젝트 정보 -->
            @if(isset($project_name))
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">프로젝트</p>
                            <p class="text-sm text-gray-500">{{ $project_name }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 안내 메시지 -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">도움이 필요하신가요?</h3>
                        <div class="mt-1 text-sm text-blue-700">
                            <p>프로젝트 관리자에게 접근 권한을 요청하거나, 프로젝트 대시보드로 돌아가세요.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 액션 버튼들 -->
            <div class="flex flex-col space-y-3">
                @if(isset($project_id))
                    <a href="{{ route('project.dashboard', ['id' => request()->route('id'), 'projectId' => $project_id]) }}" 
                       class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors text-center">
                        프로젝트 대시보드로 돌아가기
                    </a>
                @endif
                
                <a href="{{ route('organization.dashboard', ['id' => request()->route('id')]) }}" 
                   class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-md text-sm font-medium hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-center">
                    조직 대시보드로 돌아가기
                </a>
                
                <a href="{{ url('/dashboard') }}" 
                   class="w-full text-gray-600 py-2 px-4 text-sm font-medium hover:text-gray-800 transition-colors text-center">
                    메인 대시보드
                </a>
            </div>

            <!-- 추가 정보 -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    권한 관련 문의사항이 있으시면 프로젝트 관리자에게 연락하세요.
                </p>
            </div>
        </div>
    </div>
</body>
</html>