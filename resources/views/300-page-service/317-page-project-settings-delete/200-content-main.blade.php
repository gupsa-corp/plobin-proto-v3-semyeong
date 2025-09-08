<!-- 프로젝트 삭제 콘텐츠 -->
<div class="px-6 py-6" x-data="{ confirmText: '', canDelete: false }" x-init="$watch('confirmText', value => canDelete = value === '삭제')">
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

    <!-- 위험 경고 -->
    <div class="rounded-md bg-red-50 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">위험한 작업</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>이 작업은 되돌릴 수 없습니다. 프로젝트와 모든 관련 데이터가 영구적으로 삭제됩니다.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 프로젝트 삭제 폼 -->
    <div class="bg-white shadow rounded-lg border-2 border-red-200">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-red-900">프로젝트 삭제</h3>
            <p class="mt-1 text-sm text-red-700">
                이 프로젝트를 영구적으로 삭제합니다. 프로젝트에 속한 모든 페이지와 데이터도 함께 삭제됩니다.
            </p>
            
            <!-- 삭제될 내용 목록 -->
            <div class="mt-6 bg-red-50 border border-red-200 rounded-md p-4">
                <h4 class="text-sm font-medium text-red-900 mb-2">다음 내용이 삭제됩니다:</h4>
                <ul class="text-sm text-red-800 space-y-1">
                    <li>• 프로젝트의 모든 페이지</li>
                    <li>• 프로젝트 설정 및 권한</li>
                    <li>• 프로젝트 멤버 정보</li>
                    <li>• 연결된 샌드박스 정보</li>
                    <li>• 배포 히스토리</li>
                    <li>• 모든 프로젝트 관련 데이터</li>
                </ul>
            </div>

            <div class="mt-6">
                <label for="confirm-delete" class="block text-sm font-medium text-red-700">
                    삭제를 확인하려면 <strong>"삭제"</strong>를 입력하세요
                </label>
                <div class="mt-1">
                    <input type="text" 
                           name="confirm-delete" 
                           id="confirm-delete" 
                           x-model="confirmText"
                           class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-red-300 rounded-md" 
                           placeholder="삭제">
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('project.dashboard.project.settings.name', ['id' => request()->route('id'), 'projectId' => request()->route('projectId')]) }}" 
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    취소
                </a>
                <button type="button" 
                        :disabled="!canDelete"
                        :class="canDelete ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-red-300 cursor-not-allowed'"
                        class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2">
                    프로젝트 영구 삭제
                </button>
            </div>
        </div>
    </div>

    <!-- 추가 정보 -->
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-md p-4">
        <h4 class="text-sm font-medium text-gray-900 mb-2">삭제 전 확인사항:</h4>
        <ul class="text-sm text-gray-700 space-y-1">
            <li>• 중요한 데이터가 백업되었는지 확인하세요</li>
            <li>• 다른 팀원들에게 삭제 계획을 알렸는지 확인하세요</li>
            <li>• 프로덕션 환경에 배포된 내용이 있다면 먼저 처리하세요</li>
        </ul>
    </div>
</div>