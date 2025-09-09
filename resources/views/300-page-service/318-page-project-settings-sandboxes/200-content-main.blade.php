<!-- 샌드박스 관리 콘텐츠 -->
<div class="px-6 py-6" x-data="{ showAddForm: false, selectedSandbox: '', newSandboxName: '', newSandboxType: 'development', newSandboxDescription: '' }">
    <!-- 성공/에러 메시지 -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- 프로젝트로 이동 버튼 -->
    <div class="mb-6">
        <a href="{{ route('project.dashboard', ['id' => request()->route('id'), 'projectId' => request()->route('projectId')]) }}" 
           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            프로젝트로 이동
        </a>
    </div>

    <!-- 새 샌드박스 추가 버튼 -->
    <div class="mb-6">
        <button @click="showAddForm = !showAddForm" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            샌드박스 추가
        </button>
    </div>

    <!-- 새 샌드박스 추가 폼 -->
    <div x-show="showAddForm" x-transition class="mb-6">
        <form method="POST" action="{{ route('project.dashboard.project.settings.sandboxes.post', ['id' => request()->route('id'), 'projectId' => request()->route('projectId')]) }}" class="bg-white shadow rounded-lg">
            @csrf
            <input type="hidden" name="action" value="create">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">새 샌드박스 추가</h3>
                <p class="mt-1 text-sm text-gray-500">
                    프로젝트에서 사용할 새로운 샌드박스를 추가할 수 있습니다.
                </p>
                
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">샌드박스 이름</label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   x-model="newSandboxName"
                                   value="{{ old('name') }}"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 @enderror" 
                                   placeholder="예: dev-v1, test-main, demo-client">
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">샌드박스 타입</label>
                        <div class="mt-1">
                            <select id="type" 
                                    name="type" 
                                    x-model="newSandboxType"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="development" {{ old('type') == 'development' ? 'selected' : '' }}>개발용</option>
                                <option value="testing" {{ old('type') == 'testing' ? 'selected' : '' }}>테스트용</option>
                                <option value="staging" {{ old('type') == 'staging' ? 'selected' : '' }}>스테이징</option>
                                <option value="demo" {{ old('type') == 'demo' ? 'selected' : '' }}>데모용</option>
                            </select>
                        </div>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">설명 (선택사항)</label>
                    <div class="mt-1">
                        <textarea id="description" 
                                  name="description" 
                                  rows="3" 
                                  x-model="newSandboxDescription"
                                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                  placeholder="이 샌드박스의 용도를 설명해주세요.">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="showAddForm = false" 
                            type="button" 
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        취소
                    </button>
                    <button type="submit" 
                            class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        샌드박스 추가
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 현재 프로젝트 샌드박스 목록 -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">프로젝트 샌드박스</h3>
            <p class="mt-1 text-sm text-gray-500">
                현재 프로젝트에서 사용할 수 있는 모든 샌드박스입니다. 여러 개의 샌드박스를 추가할 수 있습니다.
            </p>
            
            <div class="mt-6">
                @if($sandboxes && $sandboxes->count() > 0)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($sandboxes as $sandbox)
                            <div class="border border-gray-200 rounded-lg p-4" x-data="{ showDeleteConfirm: false }">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 truncate">{{ $sandbox->name }}</h4>
                                        <p class="text-sm text-gray-500 capitalize">
                                            @switch($sandbox->settings['type'] ?? 'development')
                                                @case('development')
                                                    개발용
                                                    @break
                                                @case('testing')
                                                    테스트용
                                                    @break
                                                @case('staging')
                                                    스테이징
                                                    @break
                                                @case('demo')
                                                    데모용
                                                    @break
                                                @default
                                                    {{ $sandbox->settings['type'] ?? '기타' }}
                                            @endswitch
                                        </p>
                                        @if($sandbox->description)
                                            <p class="text-xs text-gray-400 mt-1 truncate">{{ $sandbox->description }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($sandbox->status === 'active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                활성
                                            </span>
                                        @elseif($sandbox->status === 'inactive')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                비활성
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                오류
                                            </span>
                                        @endif
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="text-gray-400 hover:text-gray-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                </svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false" x-transition
                                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                                <form method="POST" action="{{ route('project.dashboard.project.settings.sandboxes.post', ['id' => request()->route('id'), 'projectId' => request()->route('projectId')]) }}" class="block">
                                                    @csrf
                                                    <input type="hidden" name="action" value="toggle_status">
                                                    <input type="hidden" name="sandbox_id" value="{{ $sandbox->id }}">
                                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        {{ $sandbox->status === 'active' ? '비활성화' : '활성화' }}
                                                    </button>
                                                </form>
                                                <button @click="showDeleteConfirm = true; open = false" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                                    삭제
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 space-y-1">
                                    <p class="text-xs text-gray-600">추가일: {{ $sandbox->created_at->format('Y-m-d') }}</p>
                                    <p class="text-xs text-gray-600">생성자: {{ $sandbox->creator->name }}</p>
                                    <p class="text-xs text-gray-600">크기: {{ $sandbox->getSize() }}</p>
                                    <p class="text-xs text-gray-600">파일 수: {{ $sandbox->getFileCount() }}개</p>
                                    @if(!$sandbox->exists())
                                        <p class="text-xs text-red-600">⚠️ 파일 시스템에 존재하지 않음</p>
                                    @elseif(!$sandbox->databaseExists())
                                        <p class="text-xs text-orange-600">⚠️ 데이터베이스 파일 없음</p>
                                    @endif
                                </div>

                                <!-- 삭제 확인 다이얼로그 -->
                                <div x-show="showDeleteConfirm" x-transition class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                        <div class="mt-3 text-center">
                                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 14.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">샌드박스 삭제</h3>
                                            <div class="mt-2 px-7 py-3">
                                                <p class="text-sm text-gray-500">
                                                    '<span class="font-medium">{{ $sandbox->name }}</span>' 샌드박스를 정말 삭제하시겠습니까?
                                                    이 작업은 되돌릴 수 없습니다.
                                                </p>
                                            </div>
                                            <div class="flex justify-center space-x-3 mt-4">
                                                <button @click="showDeleteConfirm = false" 
                                                        class="px-4 py-2 bg-white text-gray-800 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                                    취소
                                                </button>
                                                <form method="POST" action="{{ route('project.dashboard.project.settings.sandboxes.post', ['id' => request()->route('id'), 'projectId' => request()->route('projectId')]) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="sandbox_id" value="{{ $sandbox->id }}">
                                                    <button type="submit" 
                                                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                                                        삭제
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- 샌드박스 없을 때 표시 -->
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h4 class="mt-2 text-lg font-medium text-gray-900">샌드박스가 없습니다</h4>
                        <p class="mt-1 text-sm text-gray-500">위의 버튼을 클릭하여 새로운 샌드박스를 추가하세요.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 샌드박스 사용 안내 -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">샌드박스 사용 안내</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>각 페이지는 하나의 샌드박스를 선택하여 사용할 수 있습니다</li>
                        <li>여러 개의 샌드박스를 추가하여 다양한 용도로 활용하세요</li>
                        <li>샌드박스를 삭제하면 해당 샌드박스를 사용 중인 페이지에 영향을 줄 수 있습니다</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>