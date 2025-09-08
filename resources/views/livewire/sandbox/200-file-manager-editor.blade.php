<div class="h-full flex flex-col">
    <!-- 에디터 헤더 -->
    <div class="bg-gray-100 px-4 py-2 border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            @if($isFileSelected)
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">{{ $selectedFileName }}</span>
                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ strtoupper($selectedFileExtension) }}</span>
                </div>
            @else
                <div class="flex items-center space-x-2 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm">파일을 선택하세요</span>
                </div>
            @endif
        </div>
        
        @if($isFileSelected)
            <div class="flex items-center space-x-2">
                <button wire:click="saveFile" 
                        class="px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600 transition-colors">
                    저장 (Ctrl+S)
                </button>
                <button wire:click="resetEditor" 
                        class="px-3 py-1 bg-gray-500 text-white rounded text-sm hover:bg-gray-600 transition-colors">
                    닫기
                </button>
            </div>
        @endif
    </div>

    <!-- 모나코 에디터 -->
    <div class="flex-1" wire:ignore>
        @if($isFileSelected)
            <div id="monaco-editor-container" class="w-full h-full"></div>
        @else
            <div class="flex items-center justify-center h-full bg-gray-50 text-gray-500">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-lg font-medium">파일을 선택하세요</p>
                    <p class="text-sm mt-1">왼쪽 파일 매니저에서 편집 가능한 파일을 클릭하세요</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    let monacoEditor = null;
    let currentContent = @json($selectedFileContent ?? '');
    let currentLanguage = @json($this->getEditorLanguage());
    
    // Monaco Editor 초기화
    function initMonacoEditor() {
        if (!window.monaco) {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.47.0/min/vs/loader.min.js';
            script.onload = () => {
                require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.47.0/min/vs' }});
                require(['vs/editor/editor.main'], createEditor);
            };
            document.head.appendChild(script);
        } else {
            createEditor();
        }
    }
    
    function createEditor() {
        const container = document.getElementById('monaco-editor-container');
        if (!container || monacoEditor) return;
        
        monacoEditor = monaco.editor.create(container, {
            value: currentContent || '',
            language: currentLanguage || 'plaintext',
            theme: 'vs-dark',
            automaticLayout: true,
            minimap: { enabled: false },
            scrollBeyondLastLine: false,
            fontSize: 14,
            lineNumbers: 'on',
            renderWhitespace: 'selection',
            wordWrap: 'on'
        });
        
        // 내용 변경 시 Livewire와 동기화
        monacoEditor.getModel().onDidChangeContent(() => {
            @this.set('selectedFileContent', monacoEditor.getValue());
        });
        
        // Ctrl+S로 저장
        monacoEditor.addCommand(monaco.KeyMod.CtrlCmd | monaco.KeyCode.KeyS, () => {
            @this.call('saveFile');
        });
    }
    
    // 파일이 로드될 때 에디터 업데이트
    function updateEditor(content, language) {
        if (monacoEditor) {
            if (monacoEditor.getValue() !== content) {
                monacoEditor.setValue(content || '');
            }
            monaco.editor.setModelLanguage(monacoEditor.getModel(), language || 'plaintext');
        }
        currentContent = content;
        currentLanguage = language;
    }
    
    // 에디터 초기화 및 파일 선택 이벤트 리스너
    document.addEventListener('livewire:initialized', () => {
        // 파일 선택 이벤트 리스너
        Livewire.on('fileSelected', (data) => {
            setTimeout(() => {
                @this.call('loadFile', data.mediaId);
            }, 100);
        });
        
        // 파일 저장 성공 알림
        Livewire.on('fileSaved', (data) => {
            if (data && data.message) {
                alert(data.message);
            }
        });
        
        // 컴포넌트 업데이트 후 에디터 다시 초기화
        Livewire.hook('morph.updated', () => {
            setTimeout(() => {
                if (document.getElementById('monaco-editor-container') && !monacoEditor) {
                    initMonacoEditor();
                }
            }, 100);
        });
        
        // 초기 에디터 생성
        setTimeout(() => {
            if (document.getElementById('monaco-editor-container')) {
                initMonacoEditor();
            }
        }, 100);
    });
</script>