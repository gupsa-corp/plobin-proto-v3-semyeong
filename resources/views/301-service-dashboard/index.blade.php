<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
<html lang="ko">
@include($common . '.head', ['title' => '대시보드'])
<body class="bg-gray-100">
    @php
        // 301 폴더의 데이터 파일에서 메뉴 데이터 로드  
        $menuItems = include(resource_path('views/301-service-dashboard/sidebar-data.blade.php'));
    @endphp
    
    <div class="flex min-h-screen">
        @include($common . '.sidebar', ['menuItems' => $menuItems])
        <div class="flex-1 flex flex-col">
            @include($common . '.header')
            @include(getCurrentViewPath())
            @include($common . '.footer')
        </div>
    </div>
</body>
</html>
