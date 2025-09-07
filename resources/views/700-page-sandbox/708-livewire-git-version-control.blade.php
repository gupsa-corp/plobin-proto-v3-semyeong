<div class="space-y-6">
    <!-- Git 저장소 상태 -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">저장소 상태</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm font-medium text-gray-500">저장소 상태</div>
                    <div class="mt-1">
                        @if($isGitRepo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                초기화됨
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                초기화 필요
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm font-medium text-gray-500">현재 브랜치</div>
                    <div class="mt-1 text-sm text-gray-900">{{ $currentBranch ?? '-' }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm font-medium text-gray-500">변경된 파일</div>
                    <div class="mt-1 text-sm text-gray-900">{{ count($changedFiles) }}개</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Git 초기화 -->
    @if(!$isGitRepo)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Git 저장소 초기화</h3>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">
                현재 프로젝트에 Git 저장소가 초기화되지 않았습니다. 버전 관리를 시작하려면 초기화를 진행하세요.
            </p>
            <button 
                type="button" 
                wire:click="initializeGit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Git 저장소 초기화</span>
                <span wire:loading>초기화 중...</span>
            </button>
        </div>
    </div>
    @endif

    <!-- 변경사항 확인 -->
    @if($isGitRepo)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">변경사항</h3>
        </div>
        <div class="p-6">
            @if(count($changedFiles) > 0)
                <div class="space-y-2">
                    @foreach($changedFiles as $file)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                    @if($file['status'] === 'M') bg-yellow-100 text-yellow-800
                                    @elseif($file['status'] === 'A') bg-green-100 text-green-800
                                    @elseif($file['status'] === 'D') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ $file['status'] }}
                                </span>
                                <span class="ml-3 text-sm text-gray-900">{{ $file['path'] }}</span>
                            </div>
                            <button 
                                type="button"
                                wire:click="stageFile('{{ $file['path'] }}')"
                                class="text-sm text-blue-600 hover:text-blue-500"
                                @if($file['staged']) disabled @endif
                            >
                                @if($file['staged'])
                                    스테이징됨
                                @else
                                    스테이징
                                @endif
                            </button>
                        </div>
                    @endforeach
                </div>
                
                <!-- 일괄 작업 -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex space-x-4">
                        <button 
                            type="button" 
                            wire:click="stageAll"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            모두 스테이징
                        </button>
                        <button 
                            type="button" 
                            wire:click="unstageAll"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            스테이징 해제
                        </button>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-500">변경된 파일이 없습니다.</p>
            @endif
        </div>
    </div>

    <!-- 커밋 -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">커밋</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <label for="commitMessage" class="block text-sm font-medium text-gray-700">커밋 메시지</label>
                    <textarea 
                        id="commitMessage"
                        wire:model="commitMessage"
                        rows="3" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="변경사항을 설명하세요..."
                    ></textarea>
                </div>
                <button 
                    type="button" 
                    wire:click="commit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    wire:loading.attr="disabled"
                    @if(empty($commitMessage)) disabled @endif
                >
                    <span wire:loading.remove>커밋하기</span>
                    <span wire:loading>커밋 중...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 커밋 히스토리 -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">커밋 히스토리</h3>
                <button 
                    type="button" 
                    wire:click="refreshHistory"
                    class="text-sm text-blue-600 hover:text-blue-500"
                >
                    새로고침
                </button>
            </div>
        </div>
        <div class="p-6">
            @if(count($commitHistory) > 0)
                <div class="space-y-4">
                    @foreach($commitHistory as $commit)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-700">{{ substr($commit['hash'], 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-900">{{ $commit['message'] }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $commit['author'] }} · {{ $commit['date'] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-400 font-mono">
                                    {{ substr($commit['hash'], 0, 8) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">커밋 히스토리가 없습니다.</p>
            @endif
        </div>
    </div>
    @endif

    <!-- 상태 메시지 -->
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif
</div>