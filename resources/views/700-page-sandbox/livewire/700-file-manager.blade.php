<div class="max-w-7xl mx-auto">
    <!-- 현재 경로 표시 -->
    <div class="mb-6 bg-white border rounded-lg shadow-sm p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <h3 class="font-medium text-gray-900">현재 위치:</h3>
                <span class="text-blue-600">{{ $currentPath }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <button 
                    wire:click="$refresh" 
                    class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                    새로고침
                </button>
            </div>
        </div>
    </div>

    <!-- 파일 목록 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 파일 브라우저 -->
        <div class="bg-white border rounded-lg shadow-sm">
            <div class="border-b p-4">
                <h4 class="font-medium text-gray-900">파일 브라우저</h4>
            </div>
            <div class="p-4 max-h-96 overflow-y-auto">
                <!-- 디렉토리 -->
                @if(!empty($list['dirs']))
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">폴더</h5>
                        @foreach($list['dirs'] as $dir)
                            <div class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer" 
                                 wire:click="selectDirectory('{{ $dir }}')">
                                <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                                <span class="text-sm">{{ basename($dir) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- 파일 -->
                @if(!empty($list['files']))
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 mb-2">파일</h5>
                        @foreach($list['files'] as $file)
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded cursor-pointer" 
                                 wire:click="selectFile('{{ $file }}')">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm">{{ basename($file) }}</span>
                                </div>
                                <button 
                                    wire:click="deleteFile('{{ $file }}')" 
                                    class="text-red-500 hover:text-red-700 p-1"
                                    onclick="confirm('파일을 삭제하시겠습니까?') || event.stopImmediatePropagation()">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zM8 8a1 1 0 012 0v3a1 1 0 11-2 0V8z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(empty($list['dirs']) && empty($list['files']))
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        <p>파일이 없습니다</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 파일 에디터 -->
        <div class="bg-white border rounded-lg shadow-sm">
            <div class="border-b p-4">
                <h4 class="font-medium text-gray-900">파일 에디터</h4>
            </div>
            <div class="p-4">
                <!-- 파일명 입력 -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">파일명</label>
                    <input 
                        type="text" 
                        wire:model="fileName" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="파일명을 입력하세요">
                </div>

                <!-- 파일 내용 -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">내용</label>
                    <textarea 
                        wire:model="content" 
                        rows="12" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                        placeholder="파일 내용을 입력하세요"></textarea>
                </div>

                <!-- 버튼 -->
                <div class="flex space-x-2">
                    <button 
                        wire:click="saveFile" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        저장
                    </button>
                    @if($fileName)
                        <button 
                            wire:click="togglePreview" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            미리보기
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 미리보기 모달 -->
    @if($previewMode)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg max-w-4xl max-h-[80vh] overflow-auto">
                <div class="border-b p-4 flex justify-between items-center">
                    <h3 class="font-medium">{{ $fileName }} - 미리보기</h3>
                    <button wire:click="$set('previewMode', false)" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    {!! $this->renderBladePreview() !!}
                </div>
            </div>
        </div>
    @endif

    <!-- Flash 메시지 -->
    @if(session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg">
            {{ session('message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-md shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</div>