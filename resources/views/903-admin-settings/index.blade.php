<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
<html lang="ko">
@include($common . '.head', ['title' => '시스템 설정'])
<body class="admin-body">
    <div class="flex min-h-screen">
        @include($common . '.sidebar')
        <div class="flex-1 flex flex-col">
            @include($common . '.header')
            @include(getCurrentViewPath())
            @include($common . '.footer')
        </div>
    </div>
</body>
</html>