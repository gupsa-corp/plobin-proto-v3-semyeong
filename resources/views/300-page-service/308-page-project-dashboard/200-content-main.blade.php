<div class="container mx-auto px-6 py-8">
    <!-- 프로젝트 대시보드 헤더 -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2" x-text="currentPage.title">프로젝트 대시보드</h1>
                <p class="text-gray-600" x-text="currentPage.description">프로젝트 진행 상황과 주요 메트릭을 확인하세요</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500" x-text="currentPage.breadcrumb">대시보드 홈 > 페이지 1</span>
            </div>
        </div>
    </div>

    <!-- 페이지별 커스텀 콘텐츠 -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 min-h-[500px]">
        
        <!-- 대시보드 홈 콘텐츠 -->
        <div x-show="currentPage.id === 'dashboard-home'" class="p-8">
            <div class="text-center text-gray-500">
                개발필요 - 대시보드 홈 페이지 콘텐츠
            </div>
        </div>

        <!-- 페이지 1 콘텐츠 -->
        <div x-show="currentPage.id === 'page-1'" class="p-8">
            <div class="text-center text-gray-500">
                개발필요 - 페이지 1 커스텀 콘텐츠
            </div>
        </div>

        <!-- 페이지 2 콘텐츠 -->
        <div x-show="currentPage.id === 'page-2'" class="p-8">
            <div class="text-center text-gray-500">
                개발필요 - 페이지 2 커스텀 콘텐츠
            </div>
        </div>

        <!-- 페이지 3 콘텐츠 -->
        <div x-show="currentPage.id === 'page-3'" class="p-8">
            <div class="text-center text-gray-500">
                개발필요 - 페이지 3 커스텀 콘텐츠
            </div>
        </div>

    </div>
</div>