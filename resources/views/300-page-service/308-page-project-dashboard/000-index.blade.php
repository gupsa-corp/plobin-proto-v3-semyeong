@include('000-common-layouts.001-html-lang')
<head>
    @include('300-page-service.300-common.301-layout-head')
    @include('300-page-service.300-common.302-layout-css-imports')
</head>

<body class="bg-gray-50">
    <!-- Header -->
    @include('300-page-service.308-page-project-dashboard.100-header-main')

    <!-- Main Content -->
    <div class="flex">
        <!-- Sidebar -->
        @include('300-page-service.308-page-project-dashboard.200-sidebar-main')

        <!-- Content Area -->
        <div class="ml-[220px] flex-1">
            @include('300-page-service.308-page-project-dashboard.200-content-main')
        </div>
    </div>

    <!-- JavaScript -->
    @include('300-page-service.300-common.303-layout-js-imports')
    @include('300-page-service.300-common.900-alpine-init')
</body>
</html>