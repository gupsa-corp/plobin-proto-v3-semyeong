<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '프로필'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('300-page-service.303-page-mypage-profile.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include($common . '.100-header-main')
            @include('300-page-service.303-page-mypage-profile.200-content-main')
        </div>
    </div>

    @include('300-page-service.303-page-mypage-profile.400-js-profile')
</body>
</html>
