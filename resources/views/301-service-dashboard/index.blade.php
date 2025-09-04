<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
<html lang="ko">
@include($common . '.head', ['title' => '대시보드'])
<body class="bg-gray-100">
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
