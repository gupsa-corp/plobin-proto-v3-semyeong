<div class="bg-white h-screen flex flex-col overflow-hidden">
    {{-- Tab Navigation --}}
    <div class="bg-white border-b border-gray-200">
        <div class="px-6 py-3">
            <nav class="-mb-px flex space-x-8">
                @foreach($availableTabs as $tabId => $tab)
                    <button
                        wire:click="switchTab('{{ $tabId }}')"
                        class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm transition-colors
                            {{ $activeTab === $tabId 
                                ? 'border-blue-500 text-blue-600' 
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        <span class="mr-2">{{ $tab['icon'] }}</span>
                        {{ $tab['name'] }}
                    </button>
                @endforeach
            </nav>
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="flex-1 overflow-hidden">
        
        {{-- Function Browser Tab --}}
        @if($activeTab === 'browser')
            <div
                x-data="{
                    sidebarWidth: 320,
                    previewWidth: 35, // percentage
                    isResizingSidebar: false,
                    isResizingPreview: false,
                    testParams: '{}',
                    
                    startSidebarResize(e) {
                        this.isResizingSidebar = true;
                        document.addEventListener('mousemove', this.handleSidebarResize);
                        document.addEventListener('mouseup', this.stopSidebarResize);
                        document.body.style.cursor = 'col-resize';
                        document.body.style.userSelect = 'none';
                        e.preventDefault();
                    },

                    handleSidebarResize(e) {
                        if (this.isResizingSidebar) {
                            const newWidth = Math.max(250, Math.min(600, e.clientX));
                            this.sidebarWidth = newWidth;
                        }
                    },

                    stopSidebarResize() {
                        this.isResizingSidebar = false;
                        document.removeEventListener('mousemove', this.handleSidebarResize);
                        document.removeEventListener('mouseup', this.stopSidebarResize);
                        document.body.style.cursor = '';
                        document.body.style.userSelect = '';
                    },

                    startPreviewResize(e) {
                        this.isResizingPreview = true;
                        document.addEventListener('mousemove', this.handlePreviewResize);
                        document.addEventListener('mouseup', this.stopPreviewResize);
                        document.body.style.cursor = 'col-resize';
                        document.body.style.userSelect = 'none';
                        e.preventDefault();
                    },

                    handlePreviewResize(e) {
                        if (this.isResizingPreview) {
                            const containerWidth = window.innerWidth - this.sidebarWidth;
                            const previewX = e.clientX - this.sidebarWidth;
                            const percentage = Math.max(20, Math.min(80, (containerWidth - previewX) / containerWidth * 100));
                            this.previewWidth = percentage;
                        }
                    },

                    stopPreviewResize() {
                        this.isResizingPreview = false;
                        document.removeEventListener('mousemove', this.handlePreviewResize);
                        document.removeEventListener('mouseup', this.stopPreviewResize);
                        document.body.style.cursor = '';
                        document.body.style.userSelect = '';
                    }
                }"
                class="h-full flex"
            >
                {{-- Include the existing function browser content --}}
                @include('livewire.sandbox.partials.function-browser-content')
            </div>
        @endif

        {{-- Function Creator Tab --}}
        @if($activeTab === 'creator')
            <div class="h-full overflow-auto">
                @livewire('sandbox.function-creator', key('function-creator'))
            </div>
        @endif

        {{-- Dependencies Tab --}}
        @if($activeTab === 'dependencies')
            <div class="h-full p-6 overflow-auto">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🔗</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">의존성 관리</h3>
                        <p class="text-gray-600 mb-6">함수 간 의존 관계를 관리하고 시각화합니다.</p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800">🚧 이 기능은 곧 출시될 예정입니다.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Automation Tab --}}
        @if($activeTab === 'automation')
            <div class="h-full p-6 overflow-auto">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">⚡</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">자동화 워크플로우</h3>
                        <p class="text-gray-600 mb-6">함수들을 연결하여 자동화 워크플로우를 생성합니다.</p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800">🚧 이 기능은 곧 출시될 예정입니다.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Templates Tab --}}
        @if($activeTab === 'templates')
            <div class="h-full p-6 overflow-auto">
                <div class="max-w-6xl mx-auto">
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">함수 템플릿 라이브러리</h3>
                        <p class="text-gray-600">사용 가능한 함수 템플릿을 둘러보고 새 함수 생성에 활용하세요.</p>
                    </div>

                    @if($templateService && method_exists($templateService, 'getTemplates'))
                        @php
                            $templates = $templateService->getTemplates();
                            $categories = $templateService->getCategories();
                        @endphp
                        
                        @if(!empty($templates))
                            {{-- Categories --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($templates as $templateId => $template)
                                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                        <div class="flex items-start space-x-3">
                                            <div class="text-2xl">{{ $template['icon'] ?? '📦' }}</div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900 mb-2">{{ $template['name'] }}</h4>
                                                <p class="text-sm text-gray-600 mb-3">{{ $template['description'] }}</p>
                                                
                                                {{-- Category Badge --}}
                                                @if(isset($template['category']) && isset($categories[$template['category']]))
                                                    <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded mb-3">
                                                        {{ $categories[$template['category']]['name'] }}
                                                    </span>
                                                @endif

                                                {{-- Tags --}}
                                                <div class="flex flex-wrap gap-1 mb-4">
                                                    @foreach($template['tags'] ?? [] as $tag)
                                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">{{ $tag }}</span>
                                                    @endforeach
                                                </div>

                                                {{-- Parameters --}}
                                                @if(!empty($template['parameters']))
                                                    <div class="text-xs text-gray-500 mb-3">
                                                        <strong>파라미터:</strong>
                                                        @foreach($template['parameters'] as $param)
                                                            <span class="inline-block ml-1">{{ $param['name'] }}</span>{{ !$loop->last ? ',' : '' }}
                                                        @endforeach
                                                    </div>
                                                @endif

                                                {{-- Actions --}}
                                                <div class="flex space-x-2">
                                                    <button 
                                                        wire:click="switchTab('creator')"
                                                        class="flex-1 px-3 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                                                    >
                                                        사용하기
                                                    </button>
                                                    <button class="px-3 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                                        미리보기
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="text-6xl mb-4">📝</div>
                                <p class="text-gray-600">사용 가능한 템플릿이 없습니다.</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">⚙️</div>
                            <p class="text-gray-600">템플릿 서비스를 로드하는 중입니다...</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>