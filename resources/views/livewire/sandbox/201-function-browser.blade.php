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
    class="bg-white h-screen flex overflow-hidden"
>
    {{-- 좌측 사이드바: 함수 트리 --}}
    <div class="bg-gray-50 border-r border-gray-200 flex flex-col" :style="'width: ' + sidebarWidth + 'px'">
        <div class="flex-1 overflow-hidden">
            {{-- 함수 목록 헤더 --}}
            <div class="p-4 border-b border-gray-200 bg-gray-100">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    함수 목록
                </h3>
            </div>

            {{-- 함수 목록 --}}
            <div class="flex-1 overflow-auto p-2">
                @forelse($functions as $function)
                    <div class="mb-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                        <div class="p-3 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h4 class="font-medium text-gray-800">{{ $function['name'] }}</h4>
                                <span class="text-xs text-gray-500">{{ count($function['versions']) }}개 버전</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">{{ $function['description'] }}</p>
                        </div>
                        
                        {{-- 버전 목록 --}}
                        <div class="p-2">
                            @foreach($function['versions'] as $version)
                                <button
                                    wire:click="loadFunction('{{ $function['name'] }}', '{{ $version }}')"
                                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-gray-50 flex items-center justify-between
                                        {{ $activeFunction === $function['name'] . ':' . $version ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500' : 'text-gray-700' }}"
                                >
                                    <div class="flex items-center">
                                        @if($version === 'release')
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                            <span class="font-medium">{{ $version }}</span>
                                        @else
                                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                            <span>{{ $version }}</span>
                                        @endif
                                    </div>
                                    @if($version === 'release')
                                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">활성</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        <p>함수가 없습니다.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 사이드바 리사이저 --}}
    <div
        class="w-1 bg-gray-300 hover:bg-blue-500 cursor-col-resize flex-shrink-0 transition-colors"
        @mousedown="startSidebarResize($event)"
        title="사이드바 크기 조절"
    ></div>

    {{-- 중앙: 함수 에디터 --}}
    <div class="flex flex-1 min-w-0">
        <div class="flex-1 min-w-0 flex flex-col" :style="'width: ' + (100 - previewWidth) + '%'">
            {{-- 탭 바 --}}
            @if(!empty($openTabs))
                <div class="bg-gray-100 border-b border-gray-200 flex overflow-x-auto">
                    @foreach($openTabs as $tab)
                        @php
                            [$functionName, $version] = explode(':', $tab);
                        @endphp
                        <div class="flex items-center border-r border-gray-300 min-w-0 
                                    {{ $activeFunction === $tab ? 'bg-white border-b-2 border-blue-500' : 'bg-gray-100 hover:bg-gray-200' }}">
                            <button
                                wire:click="setActiveTab('{{ $tab }}')"
                                class="px-3 py-2 text-sm truncate flex-1 min-w-0"
                                title="{{ $functionName }} ({{ $version }})"
                            >
                                <span class="font-medium">{{ $functionName }}</span>
                                <span class="text-xs text-gray-500 ml-1">({{ $version }})</span>
                            </button>
                            <button
                                wire:click="closeTab('{{ $tab }}')"
                                class="px-2 py-2 hover:bg-gray-300 text-gray-500 hover:text-gray-700"
                                title="탭 닫기"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- 코드 에디터 영역 --}}
            <div class="flex-1 flex flex-col">
                @if($activeContent)
                    <div class="flex-1 p-4">
                        <div class="mb-4 flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                                <h3 class="font-medium text-gray-800">함수 코드</h3>
                            </div>
                            <div class="flex space-x-2">
                                <button
                                    @click="$wire.saveFunction($refs.codeEditor.value)"
                                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600"
                                    title="Ctrl+S"
                                >
                                    저장
                                </button>
                            </div>
                        </div>
                        
                        <textarea
                            x-ref="codeEditor"
                            wire:model.live="functionContents.{{ $activeFunction }}"
                            class="w-full h-96 p-4 border border-gray-300 rounded-lg font-mono text-sm resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            style="font-family: 'Fira Code', 'SF Mono', Monaco, Inconsolata, 'Roboto Mono', 'Source Code Pro', monospace;"
                            placeholder="함수 코드가 여기에 표시됩니다..."
                        >{{ $activeContent }}</textarea>
                        
                        <div class="mt-2 text-xs text-gray-500">
                            <p>💡 팁: Ctrl+S로 저장하면 기존 release가 자동으로 백업됩니다.</p>
                        </div>
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center text-gray-500">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">함수 에디터</h3>
                            <p class="text-gray-600">편집할 함수를 선택하세요</p>
                            <div class="mt-4 text-sm text-gray-500">
                                <p>Ctrl+S 저장</p>
                                <p>자동 백업 지원</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- 미리보기 리사이저 --}}
        <div
            class="w-1 bg-gray-300 hover:bg-blue-500 cursor-col-resize flex-shrink-0 transition-colors"
            @mousedown="startPreviewResize($event)"
            title="테스트 패널 크기 조절"
        ></div>

        {{-- 우측: 함수 테스트 패널 --}}
        <div class="flex-shrink-0 flex flex-col bg-gray-50 border-l border-gray-200" :style="'width: ' + previewWidth + '%'">
            <div class="p-3 border-b border-gray-200 bg-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        함수 테스트
                    </h3>
                </div>
            </div>

            <div class="flex-1 flex flex-col overflow-hidden">
                @if($activeFunction)
                    {{-- 파라미터 입력 --}}
                    <div class="p-3 border-b border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">파라미터 (JSON)</label>
                        <textarea
                            x-model="testParams"
                            rows="4"
                            class="w-full p-2 border border-gray-300 rounded text-xs font-mono focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder='{"action": "get_projects", "data": {}}'
                        ></textarea>
                        <button
                            @click="$wire.testFunction(testParams)"
                            class="mt-2 w-full px-3 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600"
                        >
                            🧪 함수 실행
                        </button>
                    </div>

                    {{-- 테스트 결과 --}}
                    <div class="flex-1 overflow-auto p-3">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">실행 결과</h4>
                        
                        @forelse($testResults as $result)
                            <div class="mb-3 p-3 border rounded-lg {{ $result['success'] ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-medium {{ $result['success'] ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $result['timestamp'] }} - {{ $result['function'] }}({{ $result['version'] }})
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded {{ $result['success'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $result['success'] ? '성공' : '실패' }}
                                    </span>
                                </div>
                                
                                @if($result['success'])
                                    <div class="text-xs">
                                        <div class="mb-1 text-gray-600">결과:</div>
                                        <pre class="text-xs bg-white p-2 rounded border overflow-auto">{{ json_encode($result['result'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                @else
                                    <div class="text-xs text-red-600">
                                        <div class="mb-1">오류:</div>
                                        <div class="bg-white p-2 rounded border">{{ $result['error'] }}</div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <p class="text-sm">테스트 결과가 여기에 표시됩니다</p>
                            </div>
                        @endforelse
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center text-gray-500">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <p class="text-sm">함수를 선택하세요</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- 키보드 단축키 처리 --}}
<script>
document.addEventListener('keydown', function(e) {
    // Ctrl+S: 저장
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        const codeEditor = document.querySelector('[x-ref="codeEditor"]');
        if (codeEditor && window.Livewire) {
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('saveFunction', codeEditor.value);
        }
    }
    
    // Ctrl+Enter: 함수 실행
    if (e.ctrlKey && e.key === 'Enter') {
        e.preventDefault();
        const testParamsElement = document.querySelector('[x-model="testParams"]');
        const testParams = testParamsElement ? testParamsElement.value || '{}' : '{}';
        if (window.Livewire) {
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('testFunction', testParams);
        }
    }
});

// Livewire 이벤트 리스너
document.addEventListener('livewire:init', () => {
    Livewire.on('function-saved', (event) => {
        // 저장 완료 알림
        // 필요하면 토스트 메시지 추가
    });
    
    Livewire.on('function-tested', () => {
        // 테스트 완료 후 스크롤
        const resultsContainer = document.querySelector('.overflow-auto');
        if (resultsContainer) {
            resultsContainer.scrollTop = 0;
        }
    });
});
</script>