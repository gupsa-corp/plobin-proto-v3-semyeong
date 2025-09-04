<!-- 사용자 메뉴 -->
<div class="relative">
    <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50">
        <div class="h-8 w-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
            U
        </div>
        <span class="hidden md:block text-sm font-medium text-gray-700">사용자</span>
        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    
    @include('300-service-common-header-assets.header-assets-user-dropdown')
</div>