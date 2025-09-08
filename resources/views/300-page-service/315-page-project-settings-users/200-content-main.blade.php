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
                        <!-- 프로젝트 소유자 (항상 첫 번째로 표시) -->
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ strtoupper(substr($project->user->name ?? 'U', 0, 1)) }}{{ strtoupper(substr($project->user->name ?? 'ser', 1, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $project->user->name ?? '이름 없음' }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $project->user->email ?? '이메일 없음' }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        프로젝트 소유자
                                    </span>
                                </div>
                            </div>
                        </li>

                        <!-- 조직 멤버들 (프로젝트 소유자 제외) -->
                        @foreach($organizationMembers as $member)
                            @if($member->user_id !== $project->user_id)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ strtoupper(substr($member->user->name ?? 'U', 0, 1)) }}{{ strtoupper(substr($member->user->name ?? 'ser', 1, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $member->user->name ?? '이름 없음' }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            {{ $member->user->email ?? '이메일 없음' }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 flex items-center space-x-2">
                                        @php
                                            $userRoles = $member->user->roles->pluck('name')->toArray();
                                            $isAdmin = in_array('organization_admin', $userRoles) || in_array('platform_admin', $userRoles);
                                            $isProjectManager = in_array('project_manager', $userRoles);
                                        @endphp
                                        
                                        @if(in_array('platform_admin', $userRoles))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                플랫폼 관리자
                                            </span>
                                        @elseif(in_array('organization_admin', $userRoles))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                조직 목록자
                                            </span>
                                        @elseif($isProjectManager)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                프로젝트 관리자
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                일반 멤버
                                            </span>
                                        @endif
                                        
                                        @if(!$isAdmin)
                                        <button class="text-gray-400 hover:text-gray-600" title="멤버 제거">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            @endif
                        @endforeach

                        @if($organizationMembers->where('user_id', '!=', $project->user_id)->count() === 0)
                        <li class="py-8 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-1.654-.188-3.254-.599-4.75M7 20v-2c0-1.654.188-3.254.599-4.75M17 20v-2a3 3 0 00-3-3H9a3 3 0 00-3 3v2m8-16a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">프로젝트 멤버가 없습니다</h3>
                                <p class="mt-1 text-sm text-gray-500">프로젝트에 참여할 멤버를 초대해보세요.</p>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
