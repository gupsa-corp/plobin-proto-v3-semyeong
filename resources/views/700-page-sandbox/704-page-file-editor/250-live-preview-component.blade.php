<div class="live-preview-wrapper h-full">
    {{-- 메인 미리보기 영역 --}}
    <div class="live-preview-component w-full h-full bg-gray-50 flex flex-col">
        {{-- 미리보기 헤더 --}}
        <div class="flex items-center px-4 py-2 bg-gray-100 border-b border-gray-200">
            <span class="text-sm font-medium text-gray-600">실시간 미리보기</span>

            {{-- 미리보기 모드 선택 --}}
            <div class="ml-4 flex items-center space-x-1">
                <button
                    wire:click="setPreviewMode('combined')"
                    class="px-2 py-1 text-xs rounded {{ $previewMode === 'combined' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}"
                >
                    통합
                </button>
                <button
                    wire:click="setPreviewMode('html-only')"
                    class="px-2 py-1 text-xs rounded {{ $previewMode === 'html-only' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}"
                >
                    HTML만
                </button>
                <button
                    wire:click="setPreviewMode('mobile')"
                    class="px-2 py-1 text-xs rounded {{ $previewMode === 'mobile' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}"
                >
                    모바일
                </button>
            </div>

            <div class="ml-auto flex items-center space-x-2">
                {{-- 자동 새로고침 토글 --}}
                <button
                    wire:click="toggleAutoRefresh"
                    class="flex items-center space-x-1 px-2 py-1 text-xs rounded {{ $autoRefresh ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' }}"
                >
                    <div class="w-2 h-2 rounded-full {{ $autoRefresh ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                    <span>{{ $autoRefresh ? '실시간' : '수동' }}</span>
                </button>

                {{-- 새로고침 버튼 --}}
                <button
                    wire:click="refreshPreview"
                    class="p-1 text-gray-500 hover:text-gray-700 transition-colors"
                    title="새로고침"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>

                {{-- 전체화면 버튼 --}}
                <button
                    onclick="toggleFullscreen()"
                    class="p-1 text-gray-500 hover:text-gray-700 transition-colors"
                    title="전체화면"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- 미리보기 내용 --}}
        <div class="flex-1 relative">
            <iframe
                id="preview-frame"
                class="w-full h-full border-0"
                srcdoc="{{ $this->compiled }}"
                sandbox="allow-scripts allow-same-origin"
            ></iframe>

            {{-- 로딩 오버레이 --}}
            <div wire:loading wire:target="refreshPreview,setPreviewMode,compiled"
                 class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                <div class="flex items-center space-x-2 text-gray-600">
                    <svg class="animate-spin h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>미리보기 업데이트 중...</span>
                </div>
            </div>
        </div>

        {{-- 상태 표시 --}}
        <div class="px-4 py-2 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between text-xs text-gray-500">
                <div class="flex items-center space-x-4">
                    <span>모드: {{ ucfirst($previewMode) }}</span>
                    <span>파일: {{ count($fileContents) }}개</span>
                    <span class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span>연결됨</span>
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    @if($autoRefresh)
                        <span class="text-green-600">자동 새로고침 활성</span>
                    @endif
                    <span>{{ now()->format('H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 전체화면 모달 --}}
    <div id="fullscreen-modal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center">
        <div class="relative w-full h-full max-w-6xl max-h-screen p-4">
            <button
                onclick="toggleFullscreen()"
                class="absolute top-4 right-4 z-10 p-2 bg-white rounded-full shadow-lg hover:bg-gray-100"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <iframe
                id="fullscreen-preview"
                class="w-full h-full border-0 rounded-lg"
                srcdoc=""
                sandbox="allow-scripts allow-same-origin"
            ></iframe>
        </div>
    </div>
</div>

<script>
// 자동 새로고침 처리
@if($autoRefresh)
document.addEventListener('livewire:init', () => {
    Livewire.on('content-updated', () => {
        setTimeout(() => {
            const iframe = document.getElementById('preview-frame');
            if (iframe) {
                iframe.src = iframe.src; // 새로고침
            }
        }, 300);
    });
});
@endif

// 전체화면 토글
function toggleFullscreen() {
    const modal = document.getElementById('fullscreen-modal');
    const fullscreenPreview = document.getElementById('fullscreen-preview');
    const originalPreview = document.getElementById('preview-frame');

    if (modal.classList.contains('hidden')) {
        // 전체화면 열기
        modal.classList.remove('hidden');
        fullscreenPreview.srcdoc = originalPreview.srcdoc;
        document.body.style.overflow = 'hidden';
    } else {
        // 전체화면 닫기
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// ESC 키로 전체화면 닫기
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('fullscreen-modal');
        if (!modal.classList.contains('hidden')) {
            toggleFullscreen();
        }
    }
});
</script>
