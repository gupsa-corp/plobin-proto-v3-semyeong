<div>
    <!-- 탭 네비게이션 -->
    <div class="mb-6">
        <nav class="flex space-x-8 border-b border-gray-200" aria-label="Tabs">
            <button wire:click="switchTab('repository')" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'repository' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                저장소 상태
            </button>
            <button wire:click="switchTab('commits')" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'commits' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                커밋 관리
            </button>
            <button wire:click="switchTab('branches')" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'branches' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                브랜치 관리
            </button>
            <button wire:click="switchTab('remote')" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'remote' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                원격 저장소
            </button>
        </nav>
    </div>

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

    <!-- 저장소 상태 탭 -->
    @if($activeTab === 'repository')
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">저장소 상태</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">작업 디렉토리</p>
                            <p class="text-sm text-gray-600">{{ $workingDirectory }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button wire:click="refreshStatus" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                새로고침
                            </button>
                            <button wire:click="initRepository" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Git 초기화
                            </button>
                        </div>
                    </div>

                    @if($currentBranch)
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm font-medium text-blue-900">현재 브랜치: <span class="font-bold">{{ $currentBranch }}</span></p>
                        </div>
                    @endif

                    @if(count($uncommittedFiles) > 0)
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-yellow-900 mb-3">변경된 파일들</h4>
                            <div class="space-y-2">
                                @foreach($uncommittedFiles as $file)
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="selectedFiles" value="{{ $file['file'] }}" class="mr-3 rounded border-gray-300">
                                        <span class="text-sm text-gray-900">{{ $file['file'] }}</span>
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full 
                                            @if($file['status'] === 'Modified') bg-yellow-100 text-yellow-800
                                            @elseif($file['status'] === 'Added') bg-green-100 text-green-800
                                            @elseif($file['status'] === 'Deleted') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif
                                        ">
                                            {{ $file['status'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <button wire:click="addFiles" class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                선택된 파일 스테이지
                            </button>
                        </div>
                    @else
                        <div class="p-4 bg-green-50 rounded-lg">
                            <p class="text-sm text-green-800">변경사항이 없습니다. 작업 디렉토리가 깨끗합니다.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- 커밋 관리 탭 -->
    @if($activeTab === 'commits')
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">커밋 생성</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="commitMessage" class="block text-sm font-medium text-gray-700">커밋 메시지</label>
                        <textarea wire:model="commitMessage" id="commitMessage" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="커밋 메시지를 입력하세요"></textarea>
                    </div>
                    <button wire:click="commitChanges" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        커밋 생성
                    </button>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">최근 커밋</h3>
                
                @if(count($commits) > 0)
                    <div class="space-y-3">
                        @foreach($commits as $commit)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <code class="px-2 py-1 bg-gray-200 text-gray-800 text-xs font-mono rounded mr-3">{{ $commit['hash'] }}</code>
                                <span class="text-sm text-gray-900">{{ $commit['message'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-600">커밋이 없습니다.</p>
                @endif
            </div>
        </div>
    @endif

    <!-- 브랜치 관리 탭 -->
    @if($activeTab === 'branches')
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">브랜치 생성</h3>
                
                <div class="flex space-x-4">
                    <input type="text" wire:model="newBranchName" class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="새 브랜치 이름">
                    <button wire:click="createBranch" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        브랜치 생성
                    </button>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">브랜치 목록</h3>
                
                @if(count($branches) > 0)
                    <div class="space-y-2">
                        @foreach($branches as $branch)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $branch }}</span>
                                    @if($branch === $currentBranch)
                                        <span class="ml-2 px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">현재</span>
                                    @endif
                                </div>
                                @if($branch !== $currentBranch)
                                    <button wire:click="switchBranch('{{ $branch }}')" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                        전환
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-600">브랜치가 없습니다.</p>
                @endif
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">브랜치 병합</h3>
                
                <div class="flex space-x-4">
                    <select wire:model="mergeFromBranch" class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">병합할 브랜치 선택</option>
                        @foreach($branches as $branch)
                            @if($branch !== $currentBranch)
                                <option value="{{ $branch }}">{{ $branch }}</option>
                            @endif
                        @endforeach
                    </select>
                    <button wire:click="mergeBranch" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        병합
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 원격 저장소 탭 -->
    @if($activeTab === 'remote')
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">저장소 복제</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="cloneUrl" class="block text-sm font-medium text-gray-700">저장소 URL</label>
                        <input type="text" wire:model="cloneUrl" id="cloneUrl" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="https://github.com/username/repository.git">
                    </div>
                    <button wire:click="cloneRepository" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        저장소 복제
                    </button>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">원격 저장소 명령어</h3>
                
                <div class="space-y-3">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-900">Push (현재 브랜치를 원격으로)</p>
                        <code class="mt-1 block text-xs text-gray-600 font-mono">git push origin {{ $currentBranch ?: 'main' }}</code>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-900">Pull (원격에서 변경사항 가져오기)</p>
                        <code class="mt-1 block text-xs text-gray-600 font-mono">git pull origin {{ $currentBranch ?: 'main' }}</code>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-900">Fetch (원격 정보만 가져오기)</p>
                        <code class="mt-1 block text-xs text-gray-600 font-mono">git fetch origin</code>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <strong>주의:</strong> 원격 저장소 작업은 터미널에서 직접 실행해야 합니다. 
                        이 도구는 로컬 Git 작업만 지원합니다.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>