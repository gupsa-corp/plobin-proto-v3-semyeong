<header class="service-header bg-white">
    <div class="px-4 sm:px-6 lg:px-8 border-b border-gray-200">
        <div class="flex justify-between items-center py-4">
            <!-- 좌측: 모바일 메뉴 버튼 + 브레드크럼 -->
            <div class="flex items-center space-x-4">
                @include('300-page-service.300-common.106-header-mobile-menu')
                @include('300-page-service.300-common.102-header-breadcrumb')
            </div>

            <!-- 우측: 검색 + 알림 + 관리자 설정 + 사용자 메뉴 -->
            @include('300-page-service.300-common.107-header-right-menu')
        </div>
    </div>
</header>

@include('300-page-service.300-common.900-alpine-init')