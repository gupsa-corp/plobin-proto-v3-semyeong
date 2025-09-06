<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '대시보드'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('300-page-service.301-page-dashboard.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include($common . '.100-header-main')
            @include(getCurrentViewPath())
        </div>
    </div>
    {{-- 인증 관련 공통 함수들 --}}
    @include('300-page-service.300-common.000-auth-token-manager')
    @include('300-page-service.300-common.002-auth-logout-handler')
</body>
</html>
