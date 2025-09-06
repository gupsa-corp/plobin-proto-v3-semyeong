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
            currentPage: null,
            
            // 초기화
            init() {
                // Livewire 이벤트 리스너 등록
                window.addEventListener('pageChanged', (event) => {
                    this.currentPage = event.detail.currentPage || event.detail[0];
                });
            }
        }
    }
    
    function projectTabs() {
        return {
            showCreatePageModal: false,
            newPage: {
                name: '',
                type: 'document',
                url: '',
                description: '',
                parentId: ''
            },
            projectId: {{ request()->route('projectId') ?? 1 }},
            
            availablePages: [],
            
            async init() {
                console.log('projectTabs 초기화 시작');
                console.log('프로젝트 ID:', this.projectId);
                await this.loadAvailablePages();
                console.log('초기화 완료');
            },
            
            async loadAvailablePages() {
                try {
                    const response = await fetch(`/api/projects/${this.projectId}/tabs`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
                        credentials: 'same-origin'
                    });
                    
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.availablePages = this.flattenPages(result.data);
                    }
                } catch (error) {
                    console.error('페이지 목록 로딩 실패:', error);
                }
            },
            
            flattenPages(pages, level = 0) {
                let result = [];
                for (let page of pages) {
                    result.push({
                        id: page.id,
                        title: page.title,
                        level: level
                    });
                    if (page.children && page.children.length > 0) {
                        result = result.concat(this.flattenPages(page.children, level + 1));
                    }
                }
                return result;
            },
            
            async createSubPage() {
                if (!this.newPage.name.trim()) {
                    alert('페이지 이름을 입력해주세요.');
                    return;
                }
                
                if (this.newPage.type === 'iframe' && !this.newPage.url.trim()) {
                    alert('URL을 입력해주세요.');
                    return;
                }
                
                try {
                    const config = {
                        type: this.newPage.type,
                        description: this.newPage.description,
                        icon: this.getIconByType(this.newPage.type)
                    };
                    
                    if (this.newPage.type === 'iframe' && this.newPage.url) {
                        config.url = this.newPage.url;
                    }
                    
                    const pageData = {
                        name: this.newPage.name,
                        icon: config.icon,
                        description: this.newPage.description,
                        config: config,
                        parent_id: this.newPage.parentId || null
                    };
                    
                    const response = await fetch(`/api/projects/${this.projectId}/tabs`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(pageData)
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        // 페이지 목록 새로고침
                        await this.loadAvailablePages();
                        
                        // 새로 생성된 페이지로 이동
                        const orgId = {{ request()->route('id') ?? 1 }};
                        window.location.href = `/organizations/${orgId}/projects/${this.projectId}/pages/${result.data.id}`;
                    } else {
                        console.error('페이지 추가 실패:', result.message);
                        alert('페이지 추가에 실패했습니다: ' + result.message);
                    }
                } catch (error) {
                    console.error('페이지 추가 실패:', error);
                    alert('페이지 추가 중 오류가 발생했습니다.');
                }
            },
            
            getIconByType(type) {
                const icons = {
                    'document': 'fas fa-file-alt',
                    'iframe': 'fas fa-external-link-alt',
                    'board': 'fas fa-clipboard-list',
                    'kanban': 'fas fa-columns'
                };
                return icons[type] || 'fas fa-file';
            },
            
            resetModal() {
                this.newPage = {
                    name: '',
                    type: 'document',
                    url: '',
                    description: '',
                    parentId: ''
                };
                this.showCreatePageModal = false;
            }
        }
    }
    
    function pageContent() {
        return {
            loading: true,
            pageData: {},
            currentPageId: '{{ $currentPageId ?? "" }}',
            projectId: {{ request()->route('projectId') ?? 1 }},
            
            async init() {
                if (this.currentPageId) {
                    await this.loadPageData();
                }
            },
            
            async loadPageData() {
                try {
                    this.loading = true;
                    
                    const response = await fetch(`/api/projects/${this.projectId}/tabs/${this.currentPageId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
                        credentials: 'same-origin'
                    });
                    
                    const result = await response.json();
                    console.log('페이지 데이터 로딩 결과:', result);
                    
                    if (result.success && result.data) {
                        this.pageData = {
                            id: result.data.id,
                            title: result.data.title,
                            description: result.data.content || '',
                            status: result.data.status,
                            type: this.getPageType(result.data),
                            config: this.getPageConfig(result.data),
                            icon: this.getPageIcon(result.data)
                        };
                        console.log('파싱된 페이지 데이터:', this.pageData);
                    } else {
                        console.error('페이지 데이터 로딩 실패:', result.message);
                        this.pageData = {
                            title: '페이지를 찾을 수 없습니다',
                            description: '요청한 페이지를 불러올 수 없습니다.',
                            type: 'error'
                        };
                    }
                } catch (error) {
                    console.error('페이지 데이터 로딩 오류:', error);
                    this.pageData = {
                        title: '오류 발생',
                        description: '페이지를 불러오는 중 오류가 발생했습니다.',
                        type: 'error'
                    };
                } finally {
                    this.loading = false;
                }
            },
            
            getPageType(pageData) {
                try {
                    if (typeof pageData.content === 'string') {
                        const config = JSON.parse(pageData.content);
                        return config.type || 'document';
                    } else if (pageData.content && typeof pageData.content === 'object') {
                        return pageData.content.type || 'document';
                    }
                    return 'document';
                } catch (e) {
                    return 'document';
                }
            },
            
            getPageConfig(pageData) {
                try {
                    if (typeof pageData.content === 'string') {
                        const config = JSON.parse(pageData.content);
                        return config || {};
                    } else if (pageData.content && typeof pageData.content === 'object') {
                        return pageData.content || {};
                    }
                    return {};
                } catch (e) {
                    return {};
                }
            },
            
            getPageIcon(pageData) {
                const config = this.getPageConfig(pageData);
                const type = this.getPageType(pageData);
                
                if (config.icon) {
                    return config.icon;
                }
                
                const typeIcons = {
                    'document': 'fas fa-file-alt',
                    'iframe': 'fas fa-external-link-alt',
                    'board': 'fas fa-clipboard-list',
                    'kanban': 'fas fa-columns'
                };
                
                return typeIcons[type] || 'fas fa-file';
            }
        }
    }
    </script>

    <!-- JavaScript -->
    @include('300-page-service.300-common.303-layout-js-imports')
    @livewireScripts
</body>
</html>