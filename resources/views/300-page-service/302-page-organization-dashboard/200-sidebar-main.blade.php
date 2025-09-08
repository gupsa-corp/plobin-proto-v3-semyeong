@php
    $sidebarData = include resource_path('views/300-page-service/302-page-organization-dashboard/600-data-sidebar.blade.php');
    $navItems = $sidebarData['navigation_items'];

    // Closure를 실제 URL 문자열로 변환
    foreach ($navItems as &$item) {
        if (is_callable($item['url'])) {
            $item['url'] = $item['url']();
        }
    }
@endphp

{{-- 대시보드 사이드바 --}}
<nav class="sidebar" style="position: fixed; left: 0; top: 0; width: 240px; height: 100vh; background: #ffffff; border-right: 1px solid #E1E1E4; display: flex; flex-direction: column; z-index: 10; box-sizing: border-box;">
    @include('000-common-assets.100-logo')

    @include('300-page-service.300-common.202-sidebar-organization-info')

    @include('300-page-service.300-common.201-sidebar-navigation', ['navItems' => $navItems])
</nav>
