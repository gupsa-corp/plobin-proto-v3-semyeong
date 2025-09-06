{{-- 조직 목록 페이지 --}}
<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '조직 관리'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
    @include('300-page-service.303-page-mypage-profile.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('300-page-service.306-page-organizations-list.100-header-main')
            @include(getCurrentViewPath())
        </div>
    </div>

    {{-- Alpine.js 초기화 --}}
    @include($common . '.900-alpine-init')
</body>
</html>
