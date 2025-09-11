<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '권한'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('300-page-service.303-page-mypage-profile.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('300-page-service.306-page-mypage-permissions.100-header-main')
            @include('300-page-service.306-page-mypage-permissions.200-content-main')
        </div>
    </div>

    @include('300-page-service.306-page-mypage-permissions.400-js-permissions')
</body>
</html>