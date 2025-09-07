<div class="min-h-screen bg-gray-100" x-data="{ activeTab: @entangle('activeTab') }">
    <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">권한 관리</h1>
            <p class="mt-1 text-sm text-gray-600">역할, 권한, 동적 규칙을 관리합니다.</p>
        </div>

        <!-- 탭 네비게이션 -->
        <div class="mb-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'overview'" 
                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                        :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    <span class="flex items-center">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        권한 개요
                    </span>
                </button>
                
                <button @click="activeTab = 'roles'" 
                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                        :class="activeTab === 'roles' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    <span class="flex items-center">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        역할 관리
                    </span>
                </button>
                
                <button @click="activeTab = 'permissions'" 
                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                        :class="activeTab === 'permissions' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    <span class="flex items-center">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        권한 관리
                    </span>
                </button>
                
                <button @click="activeTab = 'rules'" 
                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                        :class="activeTab === 'rules' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                    <span class="flex items-center">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        동적 규칙
                    </span>
                </button>
            </nav>
        </div>

        <!-- 탭 콘텐츠 -->
        <div class="bg-white shadow rounded-lg">
            <div x-show="activeTab === 'overview'" style="display: none;" x-transition>
                @livewire('platform-admin.permission-overview')
            </div>
            <div x-show="activeTab === 'roles'" style="display: none;" x-transition>
                @livewire('platform-admin.role-management')
            </div>
            <div x-show="activeTab === 'permissions'" style="display: none;" x-transition>
                @livewire('platform-admin.permission-category-management')
            </div>
            <div x-show="activeTab === 'rules'" style="display: none;" x-transition>
                @livewire('platform-admin.dynamic-rule-management')
            </div>
        </div>
    </div>
</div>