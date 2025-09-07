<div
    x-data="{ 
        createNewFile() {
            const fileName = prompt('파일명을 입력하세요:');
            if (fileName && this.$wire) {
                this.$wire.call('createFile', fileName);
            }
        },
        createNewFolder() {
            const folderName = prompt('폴더명을 입력하세요:');
            if (folderName && this.$wire) {
                this.$wire.call('createFolder', folderName);
            }
        },
        refreshPreview() {
            const iframe = document.getElementById('preview-frame');
            if (iframe) {
                iframe.src = iframe.src;
            }
        }
    }"
    class="bg-white rounded-lg shadow-lg h-[85vh] flex overflow-hidden"
>
    {{-- 좌측: 파일 트리 --}}
    <div class="w-64 bg-gray-50 border-r border-gray-200 flex flex-col">
        <div class="p-3 border-b border-gray-200 bg-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-700">파일 탐색기</h3>
                <div class="flex space-x-1">
                    <button 
                        @click="createNewFile()"
                        class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded"
                        title="새 파일"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </button>
                    <button 
                        @click="createNewFolder()"
                        class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded"
                        title="새 폴더"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-auto">
            @if($this->fileTree)
                <div class="p-2">
                    @include('700-page-sandbox.704-page-file-editor.200-file-tree-item', ['items' => $this->fileTree, 'level' => 0])
                </div>
            @else
                <div class="p-4 text-center text-gray-500 text-sm">
                    파일이 없습니다
                </div>
            @endif
        </div>
    </div>

    {{-- 중앙: 에디터 --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- 탭 바 --}}
        @if(!empty($openTabs))
        <div class="flex bg-gray-100 border-b border-gray-200 overflow-x-auto">
            @foreach($openTabs as $tab)
                <div class="flex items-center group {{ $activeTab === $tab ? 'bg-white border-b-2 border-blue-500' : 'hover:bg-gray-50' }}">
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
        <div class="flex-1 flex">
            <div class="flex-1 flex flex-col">
                @if($activeTab)
                    <div class="flex items-center px-4 py-2 bg-gray-50 border-b border-gray-200 text-sm">
                        <span class="text-gray-600">{{ $activeTab }}</span>
                        <div class="ml-auto flex items-center space-x-4 text-xs text-gray-500">
                            <span>언어: {{ ucfirst($this->currentFileLanguage) }}</span>
                        </div>
                    </div>
                    <div class="flex-1 relative p-4">
                        {{ $this->form }}
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center text-gray-500">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p>편집할 파일을 선택하세요</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- 우측: 실시간 미리보기 --}}
            <div class="w-1/2 bg-gray-50 border-l border-gray-200 flex flex-col">
                <div class="flex items-center px-4 py-2 bg-gray-100 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">실시간 미리보기</span>
                    <div class="ml-auto flex items-center space-x-2">
                        <button 
                            @click="refreshPreview()"
                            class="p-1 text-gray-500 hover:text-gray-700 transition-colors"
                            title="새로고침"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                        <div class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                            실시간
                        </div>
                    </div>
                </div>
                <div class="flex-1 relative">
                    <iframe
                        id="preview-frame"
                        class="w-full h-full border-0"
                        :srcdoc="$wire.compiled"
                        sandbox="allow-scripts allow-same-origin"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</div>