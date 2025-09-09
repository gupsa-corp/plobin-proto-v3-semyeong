<div class="space-y-6">
    <!-- 헤더 -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Blade 생성기</h2>
            <p class="text-gray-600 mt-1">Laravel Blade 템플릿을 쉽고 빠르게 생성하세요</p>
        </div>
        <div class="flex gap-3">
            <button wire:click="resetForm"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>초기화</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 생성 폼 -->
        <div class="space-y-6">
            <!-- 템플릿 타입 선택 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    템플릿 타입
                </h3>

                <div class="grid grid-cols-2 gap-3">
                    <button wire:click="selectTemplate('basic')"
                            :class="$templateType === 'basic' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:border-blue-400'"
                            class="p-4 border-2 rounded-lg transition-all duration-200 text-left">
                        <div class="font-medium">기본 템플릿</div>
                        <div class="text-sm opacity-75 mt-1">간단한 HTML 구조</div>
                    </button>
                    <button wire:click="selectTemplate('component')"
                            :class="$templateType === 'component' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:border-blue-400'"
                            class="p-4 border-2 rounded-lg transition-all duration-200 text-left">
                        <div class="font-medium">컴포넌트</div>
                        <div class="text-sm opacity-75 mt-1">재사용 가능한 컴포넌트</div>
                    </button>
                    <button wire:click="selectTemplate('layout')"
                            :class="$templateType === 'layout' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:border-blue-400'"
                            class="p-4 border-2 rounded-lg transition-all duration-200 text-left">
                        <div class="font-medium">레이아웃</div>
                        <div class="text-sm opacity-75 mt-1">페이지 레이아웃 템플릿</div>
                    </button>
                    <button wire:click="selectTemplate('form')"
                            :class="$templateType === 'form' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:border-blue-400'"
                            class="p-4 border-2 rounded-lg transition-all duration-200 text-left">
                        <div class="font-medium">폼</div>
                        <div class="text-sm opacity-75 mt-1">입력 폼 템플릿</div>
                    </button>
                </div>
            </div>

            <!-- 설정 옵션 -->
            <div x-show="$wire.templateType" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    설정 옵션
                </h3>

                <div class="space-y-4">
                    <!-- 파일명 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">파일명</label>
                        <input wire:model="fileName"
                               type="text"
                               placeholder="예: user-profile.blade.php"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- 제목 -->
                    <div x-show="$wire.templateType === 'basic' || $wire.templateType === 'layout'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">페이지 제목</label>
                        <input wire:model="pageTitle"
                               type="text"
                               placeholder="페이지 제목을 입력하세요"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- 추가 옵션 -->
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input wire:model="includeHeader" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">헤더 포함</span>
                        </label>
                        <label class="flex items-center">
                            <input wire:model="includeFooter" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">푸터 포함</span>
                        </label>
                        <label class="flex items-center">
                            <input wire:model="includeScripts" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">스크립트 포함</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- 생성 버튼 -->
            <div x-show="$wire.templateType" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <button wire:click="generateTemplate"
                        wire:loading.attr="disabled"
                        :class="$wire.fileName ? 'bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700' : 'bg-gray-300 cursor-not-allowed'"
                        class="w-full py-3 px-6 text-white font-medium rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 disabled:opacity-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span wire:loading.remove>Blade 템플릿 생성</span>
                    <span wire:loading>생성 중...</span>
                </button>
            </div>
        </div>

        <!-- 미리보기 및 결과 -->
        <div class="space-y-6">
            <!-- 생성된 코드 -->
            <div x-show="$wire.generatedCode" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        생성된 코드
                    </h3>
                    <div class="flex gap-2">
                        <button wire:click="copyCode"
                                class="px-3 py-1 bg-gray-500 text-white text-sm rounded hover:bg-gray-600 transition-colors duration-200 flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span>복사</span>
                        </button>
                        <button wire:click="downloadTemplate"
                                class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600 transition-colors duration-200 flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>다운로드</span>
                        </button>
                    </div>
                </div>

                <div class="relative">
                    <pre class="bg-gray-900 text-green-400 p-4 rounded-lg text-sm overflow-x-auto whitespace-pre-wrap"><code>{{ $generatedCode }}</code></pre>
                    <button wire:click="copyCode"
                            class="absolute top-2 right-2 p-2 bg-gray-700 text-white rounded hover:bg-gray-600 transition-colors duration-200"
                            title="코드 복사">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- 미리보기 -->
            <div x-show="$wire.generatedCode" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    미리보기
                </h3>

                <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                    <div class="p-4 text-sm text-gray-600 bg-white border-b">
                        <strong>파일명:</strong> {{ $fileName ?: 'template.blade.php' }}<br>
                        <strong>템플릿 타입:</strong> {{ ucfirst($templateType) }}<br>
                        <strong>생성일:</strong> {{ now()->format('Y-m-d H:i:s') }}
                    </div>
                    <div class="p-4 bg-gray-50 min-h-32 text-sm">
                        <div class="text-gray-500 italic">실제 렌더링된 결과가 여기에 표시됩니다.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 성공 메시지 -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif
</div>