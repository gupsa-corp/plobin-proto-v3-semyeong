<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
<html lang="ko">
@include($common . '.head')
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include($common . '.header')
        @include(getCurrentViewPath())
        @include($common . '.footer')
    </div>
</body>
</html>