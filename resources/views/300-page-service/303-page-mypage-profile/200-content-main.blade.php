<div class="p-6" x-data="profilePage">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">프로필</h1>
        <p class="text-gray-600 mt-1">계정 정보를 확인할 수 있습니다.</p>
    </div>

    <!-- 로딩 표시 -->
    <div x-show="loading" class="flex justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- 에러 표시 -->
    <div x-show="error" x-text="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6"></div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-show="!loading && !error">
        <!-- 프로필 정보 -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">기본 정보</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">이름</label>
                            <p class="text-base text-gray-900 bg-gray-50 px-3 py-2 rounded-md" x-text="profile.full_name || '-'"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">이메일</label>
                            <p class="text-base text-gray-900 bg-gray-50 px-3 py-2 rounded-md" x-text="profile.email || '-'"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">연락처</label>
                            <p class="text-base text-gray-900 bg-gray-50 px-3 py-2 rounded-md" x-text="profile.formatted_phone || profile.phone_number || '-'"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">닉네임</label>
                            <p class="text-base text-gray-900 bg-gray-50 px-3 py-2 rounded-md" x-text="profile.nickname || '-'"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">가입일</label>
                            <p class="text-base text-gray-900 bg-gray-50 px-3 py-2 rounded-md" x-text="profile.created_at ? new Date(profile.created_at).toLocaleDateString('ko-KR') : '-'"></p>
                        </div>

                        <div x-show="profile.email_verified_at">
                            <label class="block text-sm font-medium text-gray-700 mb-2">이메일 인증일</label>
                            <p class="text-base text-gray-900 bg-gray-50 px-3 py-2 rounded-md" x-text="profile.email_verified_at ? new Date(profile.email_verified_at).toLocaleDateString('ko-KR') : '-'"></p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="/mypage/edit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            프로필 수정
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 조직 정보 -->
        <div>
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">소속 조직</h3>
                </div>
                <div class="p-6">
                    <template x-if="organizations && organizations.length > 0">
                        <div class="space-y-3">
                            <template x-for="org in organizations" :key="org.id">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-900" x-text="org.name"></span>
                                    <a :href="`/organizations/${org.id}/dashboard`" class="text-sm text-blue-600 hover:text-blue-800">
                                        대시보드
                                    </a>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="!organizations || organizations.length === 0">
                        <p class="text-sm text-gray-500">소속된 조직이 없습니다.</p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
