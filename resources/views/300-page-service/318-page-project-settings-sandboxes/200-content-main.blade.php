<!-- 샌드박스 관리 콘텐츠 -->
<div class="px-6 py-6" x-data="{ showAddForm: false, selectedSandbox: '', newSandboxName: '', newSandboxType: 'development' }">
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
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">새 샌드박스 추가</h3>
                <p class="mt-1 text-sm text-gray-500">
                    프로젝트에서 사용할 새로운 샌드박스를 추가할 수 있습니다.
                </p>
                
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="sandbox-name" class="block text-sm font-medium text-gray-700">샌드박스 이름</label>
                        <div class="mt-1">
                            <input type="text" 
                                   name="sandbox-name" 
                                   id="sandbox-name" 
                                   x-model="newSandboxName"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                   placeholder="예: 개발용, 테스트용, 데모용">
                        </div>
                    </div>

                    <div>
                        <label for="sandbox-type" class="block text-sm font-medium text-gray-700">샌드박스 타입</label>
                        <div class="mt-1">
                            <select id="sandbox-type" 
                                    name="sandbox-type" 
                                    x-model="newSandboxType"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="development">개발용</option>
                                <option value="testing">테스트용</option>
                                <option value="staging">스테이징</option>
                                <option value="demo">데모용</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="showAddForm = false" 
                            type="button" 
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        취소
                    </button>
                    <button type="button" 
                            class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        샌드박스 추가
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 현재 프로젝트 샌드박스 목록 -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">프로젝트 샌드박스</h3>
            <p class="mt-1 text-sm text-gray-500">
                현재 프로젝트에서 사용할 수 있는 모든 샌드박스입니다. 여러 개의 샌드박스를 추가할 수 있습니다.
            </p>
            
            <div class="mt-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- 예시 샌드박스 1 -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">개발용 샌드박스</h4>
                                <p class="text-sm text-gray-500">Development</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    활성
                                </span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-xs text-gray-600">추가일: 2024-01-15</p>
                            <p class="text-xs text-gray-600">사용 페이지: 3개</p>
                        </div>
                    </div>

                    <!-- 예시 샌드박스 2 -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">테스트용 샌드박스</h4>
                                <p class="text-sm text-gray-500">Testing</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    활성
                                </span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-xs text-gray-600">추가일: 2024-01-10</p>
                            <p class="text-xs text-gray-600">사용 페이지: 1개</p>
                        </div>
                    </div>

                    <!-- 예시 샌드박스 3 -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">데모용 샌드박스</h4>
                                <p class="text-sm text-gray-500">Demo</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    비활성
                                </span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-xs text-gray-600">추가일: 2024-01-05</p>
                            <p class="text-xs text-gray-600">사용 페이지: 0개</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 샌드박스 없을 때 표시 -->
            <!-- 
            <div class="text-center py-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h4 class="mt-2 text-lg font-medium text-gray-900">샌드박스가 없습니다</h4>
                <p class="mt-1 text-sm text-gray-500">위의 버튼을 클릭하여 새로운 샌드박스를 추가하세요.</p>
            </div>
            -->
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