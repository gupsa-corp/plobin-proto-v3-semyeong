<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
<html lang="ko">
@include($common . '.head', ['title' => '대시보드'])
<body class="bg-gray-100">
    @php
        // 301 폴더의 데이터 파일에서 메뉴 데이터 로드
        $menuItems = include(resource_path('views/301-service-dashboard/sidebar-data.php'));
    @endphp

    <div class="min-h-screen" style="position: relative;">
        @include($common . '.sidebar', ['menuItems' => $menuItems])
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include($common . '.header')
            @include(getCurrentViewPath())
        </div>
        @include('301-service-dashboard-modal-003-organization-manager.modal')
    </div>
</body>
</html>
