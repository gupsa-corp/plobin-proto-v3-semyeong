<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
<html lang="ko">
@include($common . '.301-layout-head', ['title' => '대시보드'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('300-page-service.301-page-dashboard.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include($common . '.100-header-main')
            @include(getCurrentViewPath())
        </div>
        @include('301-service-dashboard.302-modal-organization-manager')
    </div>
</body>
</html>
