<!-- 권한 관리 콘텐츠 -->
<div class="px-6 py-6" x-data="{ showCreateRoleForm: false, newRoleName: '', selectedPermissions: [] }">
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

    <!-- 커스텀 역할 생성 버튼 -->
    <div class="mb-6">
        <button @click="showCreateRoleForm = !showCreateRoleForm" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            커스텀 역할 생성
        </button>
    </div>

    <!-- 커스텀 역할 생성 폼 -->
    <div x-show="showCreateRoleForm" x-transition class="mb-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">커스텀 역할 생성</h3>
                <p class="mt-1 text-sm text-gray-500">
                    프로젝트에 특화된 커스텀 역할을 생성하고 권한을 설정할 수 있습니다.
                </p>
                
                <div class="mt-6">
                    <label for="role-name" class="block text-sm font-medium text-gray-700">역할 이름</label>
                    <div class="mt-1">
                        <input type="text" 
                               name="role-name" 
                               id="role-name" 
                               x-model="newRoleName"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                               placeholder="예: QA 담당자, 디자이너">
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700">권한 선택</label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="perm-read" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="perm-read" class="ml-3 text-sm text-gray-700">프로젝트 읽기</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="perm-write" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="perm-write" class="ml-3 text-sm text-gray-700">프로젝트 수정</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="perm-deploy" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="perm-deploy" class="ml-3 text-sm text-gray-700">배포 관리</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="perm-members" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="perm-members" class="ml-3 text-sm text-gray-700">멤버 관리</label>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="showCreateRoleForm = false" 
                            type="button" 
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        취소
                    </button>
                    <button type="button" 
                            class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        역할 생성
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 기본 역할 섹션 -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">기본 역할</h3>
            <p class="mt-1 text-sm text-gray-500">
                프로젝트에서 사용할 수 있는 기본 권한 역할입니다.
            </p>
            
            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- 읽기 권한 -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">읽기 권한</h4>
                            <p class="text-sm text-gray-500">프로젝트 내용 조회만 가능</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            기본
                        </span>
                    </div>
                    <ul class="mt-2 text-sm text-gray-600">
                        <li>• 프로젝트 조회</li>
                        <li>• 페이지 열람</li>
                    </ul>
                </div>

                <!-- 읽기/쓰기 권한 -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">읽기/쓰기 권한</h4>
                            <p class="text-sm text-gray-500">프로젝트 내용 수정 가능</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            기본
                        </span>
                    </div>
                    <ul class="mt-2 text-sm text-gray-600">
                        <li>• 프로젝트 조회/수정</li>
                        <li>• 페이지 생성/수정/삭제</li>
                    </ul>
                </div>

                <!-- PM 권한 -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">PM 권한</h4>
                            <p class="text-sm text-gray-500">프로젝트 관리 권한</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            기본
                        </span>
                    </div>
                    <ul class="mt-2 text-sm text-gray-600">
                        <li>• 프로젝트 전체 관리</li>
                        <li>• 멤버 초대/제거</li>
                        <li>• 배포 관리</li>
                    </ul>
                </div>

                <!-- 프로젝트 소유자 -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">프로젝트 소유자</h4>
                            <p class="text-sm text-gray-500">모든 권한 (1명만 가능)</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            소유자
                        </span>
                    </div>
                    <ul class="mt-2 text-sm text-gray-600">
                        <li>• 모든 프로젝트 권한</li>
                        <li>• 프로젝트 삭제</li>
                        <li>• 소유권 이전</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- 커스텀 역할 목록 -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">커스텀 역할</h3>
            <p class="mt-1 text-sm text-gray-500">
                프로젝트에 생성된 커스텀 역할을 관리할 수 있습니다.
            </p>
            
            <div class="mt-6">
                <div class="text-center py-6">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h4 class="mt-2 text-lg font-medium text-gray-900">커스텀 역할이 없습니다</h4>
                    <p class="mt-1 text-sm text-gray-500">위의 버튼을 클릭하여 새로운 커스텀 역할을 생성하세요.</p>
                </div>
            </div>
        </div>
    </div>
</div>