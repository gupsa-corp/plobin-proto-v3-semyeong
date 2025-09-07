<div>
    <!-- 상태 메시지 -->
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- 액션 버튼 -->
    <div class="mb-6 flex justify-between items-center">
        <div class="flex space-x-4">
            <button wire:click="refreshList" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                새로고침
            </button>
            <a href="/sandbox/api-creator" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                새 API 생성
            </a>
        </div>
        <div class="text-sm text-gray-600">
            총 {{ count($apis) }}개의 API
        </div>
    </div>

    <!-- API 목록 -->
    @if(count($apis) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($apis as $api)
                <div class="bg-white shadow rounded-lg p-6 border hover:border-indigo-300 transition-colors">
                    <!-- API 헤더 -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $api['name'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $api['filename'] }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <!-- 미리보기 버튼 -->
                            <button wire:click="viewApi('{{ $api['filename'] }}')" class="text-indigo-600 hover:text-indigo-800" title="미리보기">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            <!-- 복사 버튼 -->
                            <button wire:click="copyApi('{{ $api['filename'] }}')" class="text-green-600 hover:text-green-800" title="복사">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                            <!-- 삭제 버튼 -->
                            <button wire:click="deleteApi('{{ $api['filename'] }}')" wire:confirm="정말로 이 API를 삭제하시겠습니까?" class="text-red-600 hover:text-red-800" title="삭제">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- API 설명 -->
                    @if($api['description'])
                        <p class="text-gray-600 text-sm mb-4">{{ $api['description'] }}</p>
                    @endif

                    <!-- API 메서드 -->
                    @if(count($api['methods']) > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">메서드</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($api['methods'] as $method)
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                                        {{ $method }}()
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- API 라우트 -->
                    @if(count($api['routes']) > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">예상 라우트</h4>
                            <div class="space-y-1">
                                @foreach($api['routes'] as $route)
                                    <div class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">
                                        {{ $route }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- API 메타 정보 -->
                    <div class="flex justify-between items-center text-sm text-gray-500 pt-4 border-t">
                        <div>
                            생성: {{ $api['created'] !== 'Unknown' ? $api['created'] : '알 수 없음' }}
                        </div>
                        <div>
                            {{ $this->formatFileSize($api['size']) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- 빈 상태 -->
        <div class="text-center py-20">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">생성된 API가 없습니다</h3>
            <p class="mt-2 text-gray-500">API Creator를 사용하여 첫 번째 API를 생성해보세요.</p>
            <div class="mt-6">
                <a href="/sandbox/api-creator" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    새 API 생성
                </a>
            </div>
        </div>
    @endif

    <!-- API 미리보기 모달 -->
    @if($showPreview && $selectedApi)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closePreview">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white" wire:click.stop>
                <!-- 모달 헤더 -->
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $selectedApi['name'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $selectedApi['filename'] }}</p>
                    </div>
                    <button wire:click="closePreview" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- 모달 내용 -->
                <div class="space-y-4">
                    <!-- API 정보 -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium">파일명:</span> {{ $selectedApi['filename'] }}
                            </div>
                            <div>
                                <span class="font-medium">크기:</span> {{ $this->formatFileSize($selectedApi['size']) }}
                            </div>
                            <div>
                                <span class="font-medium">생성일:</span> {{ $selectedApi['created'] }}
                            </div>
                            <div>
                                <span class="font-medium">메서드 수:</span> {{ count($selectedApi['methods']) }}개
                            </div>
                        </div>
                        
                        @if($selectedApi['description'])
                            <div class="mt-3">
                                <span class="font-medium">설명:</span> {{ $selectedApi['description'] }}
                            </div>
                        @endif
                    </div>

                    <!-- 코드 미리보기 -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">소스 코드</h4>
                        <div class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-auto max-h-96">
                            <pre class="text-sm"><code>{{ $previewContent }}</code></pre>
                        </div>
                    </div>
                </div>

                <!-- 모달 푸터 -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button wire:click="copyApi('{{ $selectedApi['filename'] }}')" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        복사
                    </button>
                    <button wire:click="downloadApi('{{ $selectedApi['filename'] }}')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        다운로드
                    </button>
                    <button wire:click="closePreview" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        닫기
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>