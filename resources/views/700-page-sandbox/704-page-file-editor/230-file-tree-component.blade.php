<div class="file-tree-component h-full flex flex-col">
    {{-- 헤더 --}}
    <div class="p-3 border-b border-gray-200 bg-gray-100">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-gray-700">파일 탐색기</h3>
            <div class="flex space-x-1">
                <button 
                    onclick="createNewFile()"
                    class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded"
                    title="새 파일"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </button>
                <button 
                    onclick="createNewFolder()"
                    class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded"
                    title="새 폴더"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </button>
                <button 
                    wire:click="refreshFileTree"
                    class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded"
                    title="새로고침"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    {{-- 파일 트리 --}}
    <div class="flex-1 overflow-auto">
        @if($this->fileTree)
            <div class="p-2">
                @include('700-page-sandbox.704-page-file-editor.231-file-tree-item', ['items' => $this->fileTree, 'level' => 0])
            </div>
        @else
            <div class="p-4 text-center text-gray-500 text-sm">
                파일이 없습니다
            </div>
        @endif
    </div>
</div>

<script>
function createNewFile() {
    const fileName = prompt('파일명을 입력하세요:');
    if (fileName) {
        @this.call('createFile', fileName);
    }
}

function createNewFolder() {
    const folderName = prompt('폴더명을 입력하세요:');
    if (folderName) {
        @this.call('createFolder', folderName);
    }
}

function renameItem(oldPath) {
    const currentName = oldPath.split('/').pop();
    const newName = prompt('새 이름을 입력하세요:', currentName);
    if (newName && newName !== currentName) {
        @this.call('renameFile', oldPath, newName);
    }
}

function deleteItem(path, isFolder = false) {
    const itemType = isFolder ? '폴더' : '파일';
    if (confirm(`이 ${itemType}를 삭제하시겠습니까?`)) {
        if (isFolder) {
            @this.call('deleteFolder', path);
        } else {
            @this.call('deleteFile', path);
        }
    }
}
</script>