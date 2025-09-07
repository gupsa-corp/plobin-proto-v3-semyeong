{{-- 권한 관리 메인 컴포넌트 --}}
<div class="permission-management-container" style="padding: 24px;" x-data="permissionManagement">
    
    {{-- 탭 네비게이션 --}}
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'overview'"
                        :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    권한 개요
                </button>
                <button @click="activeTab = 'roles'"
                        :class="activeTab === 'roles' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    역할 관리
                </button>
                <button @click="activeTab = 'permissions'"
                        :class="activeTab === 'permissions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    권한 관리
                </button>
                <button @click="activeTab = 'rules'"
                        :class="activeTab === 'rules' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                    동적 규칙
                </button>
            </nav>
        </div>
    </div>

    {{-- 탭 컨텐츠 --}}
    <div class="tab-content">
        {{-- 권한 개요 탭 --}}
        <div x-show="activeTab === 'overview'">
            @include('900-page-platform-admin.905-page-permissions.components.921-permission-overview')
        </div>

        {{-- 역할 관리 탭 --}}
        <div x-show="activeTab === 'roles'">
            @include('900-page-platform-admin.905-page-permissions.components.922-role-management')
        </div>

        {{-- 권한 관리 탭 --}}
        <div x-show="activeTab === 'permissions'">
            @include('900-page-platform-admin.905-page-permissions.components.923-permission-category-management')
        </div>

        {{-- 동적 규칙 탭 --}}
        <div x-show="activeTab === 'rules'">
            @include('900-page-platform-admin.905-page-permissions.components.924-dynamic-rule-management')
        </div>
    </div>
    
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('permissionManagement', () => ({
        activeTab: 'overview',

        init() {
            console.log('Permission management initialized');
        }
    }));
});
</script>