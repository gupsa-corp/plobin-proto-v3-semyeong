<!-- 사용자 관리 콘텐츠 -->
<div class="px-6 py-6" x-data="{ showInviteForm: false, inviteEmail: '', inviteRole: 'member' }">
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

    <!-- 사용자 초대 버튼 -->
    <div class="mb-6">
        <button @click="showInviteForm = !showInviteForm" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            사용자 초대
        </button>
    </div>

    <!-- 사용자 초대 폼 -->
    <div x-show="showInviteForm" x-transition class="mb-6">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">사용자 초대</h3>
                <p class="mt-1 text-sm text-gray-500">
                    프로젝트에 새 사용자를 초대합니다. 초대된 사용자는 이메일로 초대 링크를 받게 됩니다.
                </p>
                
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="invite-email" class="block text-sm font-medium text-gray-700">이메일 주소</label>
                        <div class="mt-1">
                            <input type="email" 
                                   name="invite-email" 
                                   id="invite-email" 
                                   x-model="inviteEmail"
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                   placeholder="user@example.com">
                        </div>
                    </div>

                    <div>
                        <label for="invite-role" class="block text-sm font-medium text-gray-700">역할</label>
                        <div class="mt-1">
                            <select id="invite-role" 
                                    name="invite-role" 
                                    x-model="inviteRole"
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="member">멤버</option>
                                <option value="admin">관리자</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="showInviteForm = false" 
                            type="button" 
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        취소
                    </button>
                    <button type="button" 
                            class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        초대 보내기
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 현재 프로젝트 멤버 목록 -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">프로젝트 멤버</h3>
            <p class="mt-1 text-sm text-gray-500">
                현재 프로젝트에 참여 중인 모든 멤버를 확인하고 관리할 수 있습니다.
            </p>
            
            <div class="mt-6">
                <div class="flow-root">
                    <ul role="list" class="-my-5 divide-y divide-gray-200">
                        <!-- 예시 멤버 리스트 -->
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">JD</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        John Doe
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        john.doe@example.com
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        프로젝트 소유자
                                    </span>
                                </div>
                            </div>
                        </li>
                        
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">JS</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        Jane Smith
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        jane.smith@example.com
                                    </p>
                                </div>
                                <div class="flex-shrink-0 flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        관리자
                                    </span>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>