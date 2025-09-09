<!-- 권한 관리 콘텐츠 -->
<div class="px-6 py-6">
    <!-- 프로젝트 설정 탭 네비게이션 -->
    @include('300-page-service.314-page-project-settings-name.100-tab-navigation')

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

    <!-- Livewire 커스텀 역할 관리 컴포넌트 -->
    @livewire('project-settings-permissions', ['projectId' => request()->route('projectId'), 'organizationId' => request()->route('id')])

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
</div>