<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head')
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include($common . '.100-header-main')
        @include(getCurrentViewPath())
        @include($common . '.900-layout-footer')
    </div>
    @include('300-page-service.300-common.005-auth-token-module')
</body>
</html>
