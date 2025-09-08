<div
    x-data="{
        sidebarWidth: 350,
        previewWidth: 50,
        isResizingSidebar: false,
        isResizingPreview: false,
        
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
                const newWidth = Math.max(300, Math.min(600, e.clientX));
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
                const percentage = Math.max(30, Math.min(70, (containerWidth - previewX) / containerWidth * 100));
                this.previewWidth = percentage;
            }
        },

        stopPreviewResize() {
            this.isResizingPreview = false;
            document.removeEventListener('mousemove', this.handlePreviewResize);
            document.removeEventListener('mouseup', this.stopPreviewReview);
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
        }
    }"
    class="bg-white h-screen flex overflow-hidden"
>
    {{-- 좌측 사이드바: 문서 섹션 목록 --}}
    <div class="bg-gray-50 border-r border-gray-200 flex flex-col" :style="'width: ' + sidebarWidth + 'px'">
        {{-- 헤더 --}}
        <div class="p-4 border-b border-gray-200 bg-gray-100">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    개발자 문서
                </h3>
                <button 
                    wire:click="$set('isCreatingSection', {{ $isCreatingSection ? 'false' : 'true' }})"
                    class="p-1 text-gray-500 hover:text-gray-700 rounded"
                    title="새 섹션 추가"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </button>
            </div>
            
            {{-- 새 섹션 생성 폼 --}}
            @if($isCreatingSection)
                <div class="mb-3 p-3 bg-white rounded-lg border">
                    <input 
                        wire:model="newSectionName" 
                        type="text" 
                        placeholder="새 섹션 이름" 
                        class="w-full p-2 text-sm border rounded mb-2"
                        wire:keydown.enter="createSection"
                    >
                    <div class="flex space-x-2">
                        <button 
                            wire:click="createSection"
                            class="px-3 py-1 bg-indigo-500 text-white text-sm rounded hover:bg-indigo-600"
                        >
                            생성
                        </button>
                        <button 
                            wire:click="$set('isCreatingSection', false)"
                            class="px-3 py-1 bg-gray-300 text-gray-700 text-sm rounded hover:bg-gray-400"
                        >
                            취소
                        </button>
                    </div>
                </div>
            @endif
        </div>

        {{-- 섹션 목록 --}}
        <div class="flex-1 overflow-auto p-2">
            @forelse($sections as $section)
                <div class="mb-2 bg-white rounded-lg border border-gray-200 shadow-sm">
                    <button
                        wire:click="selectSection('{{ $section['id'] }}')"
                        class="w-full text-left p-3 rounded-lg hover:bg-gray-50 
                            {{ $selectedSection === $section['id'] ? 'bg-indigo-50 border-indigo-200' : '' }}"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-800 text-sm truncate
                                    {{ $selectedSection === $section['id'] ? 'text-indigo-800' : '' }}">
                                    {{ $section['title'] }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $section['filename'] }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ number_format($section['size']) }} bytes · 
                                    {{ date('Y-m-d H:i', $section['modified']) }}
                                </p>
                            </div>
                            <button
                                wire:click.stop="deleteSection('{{ $section['id'] }}')"
                                class="ml-2 p-1 text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100"
                                onclick="return confirm('정말 삭제하시겠습니까?')"
                                title="섹션 삭제"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </button>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm">문서가 없습니다.</p>
                    <p class="text-xs text-gray-400 mt-1">새 섹션을 추가해보세요.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- 사이드바 리사이저 --}}
    <div
        class="w-1 bg-gray-300 hover:bg-indigo-500 cursor-col-resize flex-shrink-0 transition-colors"
        @mousedown="startSidebarResize($event)"
        title="사이드바 크기 조절"
    ></div>

    {{-- 중앙: 에디터와 미리보기 --}}
    <div class="flex flex-1 min-w-0">
        {{-- 마크다운 에디터 --}}
        <div class="flex-1 min-w-0 flex flex-col" :style="'width: ' + (100 - previewWidth) + '%'">
            {{-- 에디터 헤더 --}}
            <div class="bg-gray-100 border-b border-gray-200 p-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-medium text-gray-800 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        마크다운 에디터
                    </h3>
                    <div class="flex space-x-2">
                        @if($selectedSection)
                            <button
                                wire:click="saveContent"
                                class="px-3 py-1 bg-indigo-500 text-white text-sm rounded hover:bg-indigo-600"
                            >
                                💾 저장
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 에디터 영역 --}}
            <div class="flex-1 flex flex-col">
                @if($selectedSection)
                    <textarea
                        wire:model.live.debounce.500ms="content"
                        class="flex-1 p-4 font-mono text-sm resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="마크다운으로 문서를 작성하세요..."
                        style="font-family: 'Fira Code', 'SF Mono', Monaco, Inconsolata, 'Roboto Mono', 'Source Code Pro', monospace;"
                    >{{ $content }}</textarea>
                    
                    <div class="p-2 bg-gray-50 border-t text-xs text-gray-500">
                        <p>💡 팁: 실시간으로 우측에서 미리보기를 확인할 수 있습니다. Ctrl+S로 저장하세요.</p>
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center text-gray-500">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">문서 에디터</h3>
                            <p class="text-gray-600">편집할 문서 섹션을 선택하세요</p>
                            <div class="mt-4 text-sm text-gray-500">
                                <p># 헤더</p>
                                <p>**굵은 글씨** *기울임*</p>
                                <p>`인라인 코드`</p>
                                <p>- 목록 항목</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- 미리보기 리사이저 --}}
        @if($selectedSection)
            <div
                class="w-1 bg-gray-300 hover:bg-indigo-500 cursor-col-resize flex-shrink-0 transition-colors"
                @mousedown="startPreviewResize($event)"
                title="미리보기 크기 조절"
            ></div>
        @endif

        {{-- 우측: 미리보기 --}}
        @if($selectedSection)
            <div class="flex-shrink-0 flex flex-col bg-white border-l border-gray-200" :style="'width: ' + previewWidth + '%'">
                <div class="p-3 border-b border-gray-200 bg-gray-100">
                    <h3 class="font-medium text-gray-800 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        미리보기
                    </h3>
                </div>
                
                <div class="flex-1 overflow-auto p-4 prose prose-sm max-w-none">
                    <div class="markdown-preview">
                        {!! $previewContent !!}
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- 플래시 메시지 --}}
    @if(session()->has('message'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50"
        >
            {{ session('message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50"
        >
            {{ session('error') }}
        </div>
    @endif
</div>

{{-- 키보드 단축키 처리 --}}
<script>
document.addEventListener('keydown', function(e) {
    // Ctrl+S: 저장
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        if (window.Livewire) {
            const component = document.querySelector('[wire\\:id]');
            if (component) {
                Livewire.find(component.getAttribute('wire:id')).call('saveContent');
            }
        }
    }
});
</script>