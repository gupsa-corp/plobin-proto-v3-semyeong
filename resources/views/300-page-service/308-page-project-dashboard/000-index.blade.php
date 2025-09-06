@include('000-common-layouts.001-html-lang')
@include('300-page-service.300-common.301-layout-head', ['title' => '프로젝트 대시보드'])
<body class="bg-gray-100" x-data="projectDashboard()">
    <div class="min-h-screen" style="position: relative;">
        @include('300-page-service.308-page-project-dashboard.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('300-page-service.308-page-project-dashboard.100-header-main')
            @include('300-page-service.308-page-project-dashboard.200-content-main')
        </div>

    </div>

    <script>
    function projectDashboard() {
        return {
            // 현재 프로젝트 ID (URL에서 추출)
            projectId: {{ request()->route('projectId') ?? 1 }},
            
            // 현재 페이지 상태 (Livewire 컴포넌트에서 업데이트)
            currentPage: {
                id: 'dashboard-home',
                title: '프로젝트 대시보드',
                description: '프로젝트 진행 상황과 주요 메트릭을 확인하세요',
                breadcrumb: '대시보드 홈'
            },
            
            // 초기화
            init() {
                // Livewire 이벤트 리스너 등록
                window.addEventListener('pageChanged', (event) => {
                    this.currentPage = event.detail.currentPage || event.detail[0];
                });
            }
        }
    }
    </script>

    <!-- JavaScript -->
    @include('300-page-service.300-common.303-layout-js-imports')
    @livewireScripts
</body>
</html>