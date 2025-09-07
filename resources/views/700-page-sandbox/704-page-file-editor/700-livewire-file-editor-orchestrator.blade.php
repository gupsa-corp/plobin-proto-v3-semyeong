<div 
    x-data="{ 
        showSearch: false,
        showVersionControl: false,
        sidebarTab: 'files' // files, search, versions
    }"
    class="bg-white h-screen flex overflow-hidden"
>
    {{-- 좌측 사이드바 --}}
    <div class="w-80 bg-gray-50 border-r border-gray-200 flex flex-col">
        {{-- 사이드바 탭 --}}
        <div class="flex border-b border-gray-200 bg-gray-100">
            <button 
                @click="sidebarTab = 'files'"
                :class="sidebarTab === 'files' ? 'bg-white border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50'"
                class="flex-1 px-3 py-2 text-sm font-medium transition-colors"
            >
                <div class="flex items-center justify-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v1H8V5z"/>
                    </svg>
                    <span>파일</span>
                </div>
            </button>
            
            <button 
                @click="sidebarTab = 'search'"
                :class="sidebarTab === 'search' ? 'bg-white border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50'"
                class="flex-1 px-3 py-2 text-sm font-medium transition-colors"
            >
                <div class="flex items-center justify-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>검색</span>
                </div>
            </button>
            
            <button 
                @click="sidebarTab = 'versions'"
                :class="sidebarTab === 'versions' ? 'bg-white border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50'"
                class="flex-1 px-3 py-2 text-sm font-medium transition-colors"
            >
                <div class="flex items-center justify-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>버전</span>
                </div>
            </button>
        </div>
        
        {{-- 사이드바 내용 --}}
        <div class="flex-1 overflow-hidden">
            {{-- 파일 트리 --}}
            <div x-show="sidebarTab === 'files'" x-transition class="h-full">
                @livewire('sandbox.file-tree.component')
            </div>
            
            {{-- 파일 검색 --}}
            <div x-show="sidebarTab === 'search'" x-transition class="h-full">
                @livewire('sandbox.file-search.component')
            </div>
            
            {{-- 버전 관리 --}}
            <div x-show="sidebarTab === 'versions'" x-transition class="h-full">
                @livewire('sandbox.file-version-control.component')
            </div>
        </div>
    </div>

    {{-- 중앙: 에디터 영역 --}}
    <div class="flex-1 flex">
        @livewire('sandbox.code-editor.component')
    </div>

    {{-- 우측: 실시간 미리보기 --}}
    @livewire('sandbox.live-preview.component')
</div>

{{-- 전역 이벤트 리스너 --}}
<script>
document.addEventListener('livewire:init', () => {
    // 파일 선택 이벤트 처리
    Livewire.on('file-selected', (event) => {
        // 코드 에디터에서 파일 열기
        Livewire.dispatch('openFile', { filePath: event.path });
        // 버전 관리에서 현재 파일 설정
        Livewire.dispatch('setCurrentFile', { filePath: event.path });
    });
    
    // 파일 생성/삭제 이벤트 처리
    Livewire.on('file-created', (event) => {
        // 파일 트리 새로고침
        Livewire.dispatch('refreshFileTree');
        // 생성된 파일 자동으로 열기
        Livewire.dispatch('openFile', { filePath: event.path });
    });
    
    Livewire.on('file-deleted', (event) => {
        // 파일 트리 새로고침
        Livewire.dispatch('refreshFileTree');
        // 삭제된 파일이 열려있다면 탭 닫기
        Livewire.dispatch('closeTab', { tabPath: event.path });
    });
    
    Livewire.on('file-renamed', (event) => {
        // 파일 트리 새로고침
        Livewire.dispatch('refreshFileTree');
        // 이름이 변경된 파일 다시 열기
        Livewire.dispatch('closeTab', { tabPath: event.oldPath });
        Livewire.dispatch('openFile', { filePath: event.newPath });
    });
    
    // 버전 복원 이벤트 처리
    Livewire.on('file-restored', (event) => {
        // 에디터에 복원된 내용 반영
        // 이는 에디터 컴포넌트에서 처리해야 함
    });
});

// 키보드 단축키
document.addEventListener('keydown', function(e) {
    // Ctrl+Shift+P: 검색 탭으로 이동
    if (e.ctrlKey && e.shiftKey && e.key === 'P') {
        e.preventDefault();
        Alpine.store('global').sidebarTab = 'search';
    }
    
    // Ctrl+Shift+G: 버전 관리 탭으로 이동
    if (e.ctrlKey && e.shiftKey && e.key === 'G') {
        e.preventDefault();
        Alpine.store('global').sidebarTab = 'versions';
    }
    
    // Ctrl+Shift+E: 파일 탭으로 이동
    if (e.ctrlKey && e.shiftKey && e.key === 'E') {
        e.preventDefault();
        Alpine.store('global').sidebarTab = 'files';
    }
});
</script>