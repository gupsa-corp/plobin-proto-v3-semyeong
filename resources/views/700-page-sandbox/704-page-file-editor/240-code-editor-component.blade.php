<div class="code-editor-component flex-1 flex flex-col min-w-0">
    {{-- 탭 바 --}}
    @if(!empty($openTabs))
    <div class="flex bg-gray-100 border-b border-gray-200 overflow-x-auto" wire:key="tab-bar">
        @foreach($openTabs as $tab)
            <div class="flex items-center group {{ $activeTab === $tab ? 'bg-white border-b-2 border-blue-500' : 'hover:bg-gray-50' }}" wire:key="tab-{{ $tab }}">
                <button 
                    wire:click="setActiveTab('{{ $tab }}')"
                    class="px-3 py-2 text-sm whitespace-nowrap flex items-center space-x-2 min-w-0"
                >
                    <span class="flex-shrink-0">
                        @php
                            $ext = pathinfo($tab, PATHINFO_EXTENSION);
                            $icon = match($ext) {
                                'html' => '🌐',
                                'css' => '🎨',
                                'js' => '⚡',
                                'php' => '🔥',
                                'json' => '📊',
                                'md' => '📝',
                                default => '📄'
                            };
                        @endphp
                        {{ $icon }}
                    </span>
                    <span class="truncate">{{ basename($tab) }}</span>
                    @if($activeTab === $tab)
                        <span class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0" title="활성"></span>
                    @endif
                </button>
                <button 
                    wire:click="closeTab('{{ $tab }}')"
                    class="p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity"
                >
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
    @endif

    {{-- 에디터 영역 --}}
    <div class="flex-1 flex flex-col">
        @if($activeTab)
            {{-- 파일 정보 바 --}}
            <div class="flex items-center px-4 py-2 bg-gray-50 border-b border-gray-200 text-sm">
                <span class="text-gray-600">{{ $activeTab }}</span>
                <div class="ml-auto flex items-center space-x-4 text-xs text-gray-500">
                    <span>파일 타입: {{ pathinfo($activeTab, PATHINFO_EXTENSION) ?: '텍스트' }}</span>
                    <span>인코딩: UTF-8</span>
                    <button 
                        wire:click="saveCurrentFile"
                        class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                        title="저장 (Ctrl+S)"
                    >
                        저장
                    </button>
                </div>
            </div>
            
            {{-- 코드 에디터 --}}
            <div class="flex-1 relative p-4">
                {{ $this->form }}
            </div>
        @else
            {{-- 빈 상태 --}}
            <div class="flex-1 flex items-center justify-center text-gray-500">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-medium mb-2">코드 에디터</h3>
                    <p>편집할 파일을 선택하세요</p>
                    <div class="mt-4 text-xs space-y-1">
                        <p><kbd class="px-2 py-1 bg-gray-100 rounded">Ctrl+S</kbd> 저장</p>
                        <p><kbd class="px-2 py-1 bg-gray-100 rounded">Ctrl+Z</kbd> 되돌리기</p>
                        <p><kbd class="px-2 py-1 bg-gray-100 rounded">Ctrl+Y</kbd> 다시하기</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- 키보드 단축키 처리 --}}
<script>
document.addEventListener('keydown', function(e) {
    // Ctrl+S 저장
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        @this.call('saveCurrentFile');
    }
});
</script>