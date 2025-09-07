<!-- 파일 목록 -->
<div class="bg-gray-50 p-4 rounded">
    <h3 class="font-medium text-gray-900 mb-3">{{ $currentPath }}</h3>

    <!-- 하위 디렉토리 -->
    @if(!empty($list['dirs']))
        <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-700 mb-2">디렉토리</h4>
            @foreach($list['dirs'] as $dir)
                <button wire:click="selectDirectory('{{ $dir }}')"
                        class="block w-full text-left px-2 py-1 text-sm text-blue-600 hover:bg-white rounded">
                    📁 {{ basename($dir) }}
                </button>
            @endforeach
        </div>
    @endif

    <!-- 파일 목록 -->
    @if(!empty($list['files']))
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-2">파일</h4>
            @foreach($list['files'] as $file)
                <div class="flex items-center justify-between py-1">
                    <button wire:click="selectFile('{{ $file }}')"
                            class="text-left text-sm text-gray-900 hover:text-blue-600">
                        📄 {{ basename($file) }}
                    </button>
                    <button wire:click="deleteFile('{{ $file }}')"
                            wire:confirm="정말로 이 파일을 삭제하시겠습니까?"
                            class="text-red-500 hover:text-red-700 text-xs">
                        삭제
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    @if(empty($list['files']) && empty($list['dirs']))
        <p class="text-gray-500 text-sm">파일이 없습니다.</p>
    @endif
</div>
