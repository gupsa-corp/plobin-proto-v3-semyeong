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
            currentPage: {
                id: 'page-1', // 기본 활성 페이지
                title: '페이지 1',
                description: '페이지 1의 커스텀 콘텐츠를 확인하세요',
                breadcrumb: '대시보드 홈 > 페이지 1'
            },
            
            pages: {
                'dashboard-home': {
                    id: 'dashboard-home',
                    title: '프로젝트 대시보드',
                    description: '프로젝트 진행 상황과 주요 메트릭을 확인하세요',
                    breadcrumb: '대시보드 홈'
                },
                'page-1': {
                    id: 'page-1',
                    title: '페이지 1',
                    description: '페이지 1의 커스텀 콘텐츠를 확인하세요',
                    breadcrumb: '대시보드 홈 > 페이지 1'
                },
                'page-2': {
                    id: 'page-2',
                    title: '페이지 2',
                    description: '페이지 2의 커스텀 콘텐츠를 확인하세요',
                    breadcrumb: '대시보드 홈 > 페이지 2'
                },
                'page-3': {
                    id: 'page-3',
                    title: '페이지 3',
                    description: '페이지 3의 커스텀 콘텐츠를 확인하세요',
                    breadcrumb: '대시보드 홈 > 페이지 3'
                }
            },

            switchPage(pageId) {
                if (this.pages[pageId]) {
                    this.currentPage = this.pages[pageId];
                }
            }
        }
    }
    </script>

    <!-- JavaScript -->
    @include('300-page-service.300-common.303-layout-js-imports')
    @include('300-page-service.300-common.900-alpine-init')
</body>
</html>