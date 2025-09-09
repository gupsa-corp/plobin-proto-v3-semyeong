<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- 메인 타이틀 -->
        <div class="flex items-center justify-between h-16">
            <a href="/sandbox" class="text-xl font-bold text-gray-900 hover:text-gray-700">
                샌드박스
            </a>

            <!-- 템플릿 스토리지 표시 -->
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-600">
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-md">
                        🎨 템플릿 스토리지 모드
                    </span>
                </div>
                <span class="text-gray-300">|</span>
                <a href="{{ route('sandbox.using-projects') }}"
                   class="text-sm text-yellow-600 hover:text-yellow-800 underline">
                    사용 프로젝트
                </a>
            </div>
        </div>

        <!-- 네비게이션 메뉴 -->
        <div class="pb-4">
            <!-- 시스템 관리 -->
            <div class="mb-3">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">시스템 관리</div>
                <div class="flex flex-wrap gap-2">
                    <a href="/sandbox/dashboard" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        대시보드
                    </a>
                    <a href="/sandbox/database-manager" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        데이터베이스 매니저
                    </a>
                    <a href="/sandbox/sql-executor" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        SQL 실행기
                    </a>
                    <a href="/sandbox/storage-manager" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        스토리지 관리자
                    </a>
                </div>
            </div>

            <!-- 파일 관리 -->
            <div class="mb-3">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">파일 관리</div>
                <div class="flex flex-wrap gap-2">
                    <a href="/sandbox/file-manager" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        파일 매니저
                    </a>
                    <a href="/sandbox/file-editor" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        파일 에디터
                    </a>
                </div>
            </div>

            <!-- 개발 도구 -->
            <div class="mb-2">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">템플릿 화면 관리</div>
                <div class="flex flex-wrap gap-2">
                    <!-- 화면 개발 (커스텀 화면) - 현재 활성화 -->
                    <a href="/sandbox/custom-screens" class="inline-flex items-center px-3 py-1 text-sm text-white bg-indigo-600 rounded-md transition-colors font-medium border border-indigo-600">
                        🎨 템플릿 화면 관리
                    </a>
                    <a href="/sandbox/custom-screen-creator" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors font-medium border border-indigo-200">
                        ✨ 화면 생성기
                    </a>
                </div>
            </div>

            <!-- 기타 도구 -->
            <div class="mb-2">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">기타 도구</div>
                <div class="flex flex-wrap gap-2">
                    <!-- API 개발 -->
                    <a href="/sandbox/api-creator" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        🔧 API 생성기
                    </a>
                    <a href="/sandbox/api-list" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        📋 API 목록
                    </a>
                    
                    <!-- 기존 블레이드 도구 -->
                    <a href="/sandbox/blade-creator" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        🎨 Blade 생성기
                    </a>
                    
                    <!-- 함수 개발 -->
                    <a href="/sandbox/function-browser" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-md transition-colors font-medium">
                        📚 함수 브라우저
                    </a>
                    
                    <!-- 기타 도구 -->
                    <a href="/sandbox/form-creator" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-md transition-colors font-medium">
                        📝 Form Creator
                    </a>
                    <a href="/sandbox/scenario-manager" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors font-medium">
                        📋 시나리오 관리자
                    </a>
                    <a href="/sandbox/git-version-control" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                        🔀 Git 버전 관리
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>