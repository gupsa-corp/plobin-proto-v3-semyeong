@include('000-common-layouts.001-html-lang')
@include('300-page-service.300-common.301-layout-head', ['title' => '프로젝트 설정 - 샌드박스 관리'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('300-page-service.308-page-project-dashboard.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('300-page-service.308-page-project-dashboard.100-header-main')
            @include('300-page-service.318-page-project-settings-sandboxes.200-content-main')
        </div>
    </div>

    <!-- JavaScript -->
    @include('300-page-service.300-common.303-layout-js-imports')
</body>
</html>