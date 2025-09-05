<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
<html lang="ko">
@include($common . '.301-layout-head')
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include($common . '.100-header-main')
        @include(getCurrentViewPath())
        @include($common . '.900-layout-footer')
    </div>
    @include('300-page-service.300-common.000-auth-token-manager')
</body>
</html>
