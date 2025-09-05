<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.head')
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include($common . '.header')
        @include(getCurrentViewPath())
        @include($common . '.footer')
    </div>
</body>
</html>
