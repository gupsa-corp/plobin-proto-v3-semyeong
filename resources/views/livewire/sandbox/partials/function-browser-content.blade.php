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
                    <button 
                        wire:click="switchTab('creator')"
                        class="mt-2 px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                        함수 생성하기
                    </button>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- 2차 사이드바: release 폴더 파일 목록 --}}
@if(!empty($folderFiles))
    <div class="bg-gray-50 border-r border-gray-200 flex flex-col w-72">
        <div class="p-3 border-b border-gray-200 bg-gray-100">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-5L12 5H5a2 2 0 00-2 2z"/>
                </svg>
                Release 폴더 파일
            </h3>
        </div>
        
        <div class="flex-1 overflow-auto p-2">
            @foreach($folderFiles as $file)
                <button
                    wire:click="selectFile('{{ $file['name'] }}')"
                    class="w-full text-left px-3 py-2 text-sm rounded hover:bg-gray-50 flex items-center mb-1
                        {{ $selectedFile === $file['name'] ? 'bg-blue-50 text-blue-700 border-l-2 border-blue-500' : 'text-gray-700' }}"
                >
                    <div class="flex items-center min-w-0 flex-1">
                        @if($file['isPhp'])
                            <svg class="w-4 h-4 mr-2 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @endif
                        <span class="truncate">{{ $file['name'] }}</span>
                    </div>
                    <div class="text-xs text-gray-500 ml-2 flex-shrink-0">
                        {{ number_format($file['size']) }}B
                    </div>
                </button>
            @endforeach
        </div>
    </div>
@endif

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
            @if($activeContent || $selectedFileContent)
                <div class="flex-1 flex flex-col">
                    {{-- 메인 함수 코드 --}}
                    @if($activeContent)
                        <div class="flex-1 p-4 border-b border-gray-200">
                            <div class="mb-4 flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                    </svg>
                                    <h3 class="font-medium text-gray-800">함수 코드 (Function.php)</h3>
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
                                class="w-full h-64 p-4 border border-gray-300 rounded-lg font-mono text-sm resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                style="font-family: 'Fira Code', 'SF Mono', Monaco, Inconsolata, 'Roboto Mono', 'Source Code Pro', monospace;"
                                placeholder="함수 코드가 여기에 표시됩니다..."
                            >{{ $activeContent }}</textarea>
                            
                            <div class="mt-2 text-xs text-gray-500">
                                <p>💡 팁: Ctrl+S로 저장하면 기존 release가 자동으로 백업됩니다.</p>
                            </div>
                        </div>
                    @endif

                    {{-- 선택된 파일 내용 --}}
                    @if($selectedFileContent && $selectedFile)
                        <div class="flex-1 p-4">
                            <div class="mb-4 flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="font-medium text-gray-800">{{ $selectedFile }}</h3>
                                </div>
                                <button
                                    wire:click="selectFile('')"
                                    class="px-2 py-1 text-gray-500 hover:text-gray-700 text-sm"
                                    title="닫기"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="w-full h-64 p-4 border border-gray-300 rounded-lg bg-gray-50 overflow-auto">
                                <pre class="font-mono text-xs text-gray-800 whitespace-pre-wrap"
                                     style="font-family: 'Fira Code', 'SF Mono', Monaco, Inconsolata, 'Roboto Mono', 'Source Code Pro', monospace;"
                                >{{ $selectedFileContent }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="flex-1 flex items-center justify-center text-gray-500">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">함수 에디터</h3>
                        <p class="text-gray-600 mb-4">편집할 함수를 선택하세요</p>
                        <div class="space-x-2">
                            <button 
                                wire:click="switchTab('creator')"
                                class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700"
                            >
                                ✨ 새 함수 생성
                            </button>
                            <button 
                                wire:click="switchTab('templates')"
                                class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50"
                            >
                                🏪 템플릿 보기
                            </button>
                        </div>
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
                {{-- Include the rest of the test panel content --}}
                @include('livewire.sandbox.partials.function-test-panel')
            @else
                <div class="flex-1 flex items-center justify-center text-gray-500">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <p class="text-sm">함수를 선택하세요</p>
                        
                        {{-- Global Functions만 표시 --}}
                        <div class="mt-8 text-left max-w-xs">
                            @include('livewire.sandbox.partials.global-functions-panel')
                        </div>
                    </div>
                </div>
            @endif
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