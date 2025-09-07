<div class="space-y-6">
    <!-- 알림 메시지 -->
    @if (session()->has('message'))
        <div class="border px-4 py-3 rounded bg-blue-100 border-blue-400 text-blue-700">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="border px-4 py-3 rounded bg-red-100 border-red-400 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <!-- 디렉토리 네비게이션 -->
    <div class="bg-gray-50 p-4 rounded">
        <h3 class="font-medium text-gray-900 mb-3">디렉토리 선택</h3>
        <div class="grid grid-cols-3 gap-2">
            <button wire:click="selectDirectory('files/views')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/views' ? 'ring-2 ring-blue-500' : '' }}">
                Views
            </button>
            <button wire:click="selectDirectory('files/controllers')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/controllers' ? 'ring-2 ring-blue-500' : '' }}">
                Controllers
            </button>
            <button wire:click="selectDirectory('files/models')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/models' ? 'ring-2 ring-blue-500' : '' }}">
                Models
            </button>
            <button wire:click="selectDirectory('files/livewire')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/livewire' ? 'ring-2 ring-blue-500' : '' }}">
                Livewire
            </button>
            <button wire:click="selectDirectory('files/routes')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/routes' ? 'ring-2 ring-blue-500' : '' }}">
                Routes
            </button>
            <button wire:click="selectDirectory('files/migrations')"
                    class="px-3 py-2 text-sm bg-white border rounded hover:bg-gray-50 {{ $currentPath === 'files/migrations' ? 'ring-2 ring-blue-500' : '' }}">
                Migrations
            </button>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
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

        <!-- 파일 편집기 -->
        <div class="col-span-2">            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">파일명</label>
                <input wire:model.live="fileName"
                       value="{{ $fileName }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="파일명을 입력하세요">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">내용</label>
                <textarea wire:model="content"
                          rows="20"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                          placeholder="파일 내용을 입력하세요">{{ $content }}</textarea>
            </div>

            <div class="flex space-x-3">
                <button wire:click="saveFile"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    저장
                </button>
                <button wire:click="refreshList"
                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    새로고침
                </button>
            </div>
        </div>
    </div>
</div>