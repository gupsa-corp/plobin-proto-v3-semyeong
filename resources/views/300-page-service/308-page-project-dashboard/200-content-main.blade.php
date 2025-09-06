<!-- 페이지별 커스텀 콘텐츠 -->
<div class="px-6 py-6">
    
    <!-- 대시보드 홈 콘텐츠 -->
    <div x-show="currentPage.id === 'dashboard-home'">
        <div class="text-center text-gray-500">
            개발필요 - 대시보드 홈 페이지 콘텐츠
        </div>
    </div>

    <!-- 페이지 1 콘텐츠 -->
    <div x-show="currentPage.id === 'page-1'">
        <div class="text-center text-gray-500">
            개발필요 - 페이지 1 커스텀 콘텐츠
        </div>
    </div>

    <!-- 페이지 2 콘텐츠 -->
    <div x-show="currentPage.id === 'page-2'">
        <div class="text-center text-gray-500">
            개발필요 - 페이지 2 커스텀 콘텐츠
        </div>
    </div>

    <!-- 페이지 3 콘텐츠 -->
    <div x-show="currentPage.id === 'page-3'">
        <div class="text-center text-gray-500">
            개발필요 - 페이지 3 커스텀 콘텐츠
        </div>
    </div>

</div>