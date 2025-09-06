{{-- 프로젝트 대시보드 헤더 --}}
<div class="bg-white border-b border-gray-200">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            {{-- 페이지 타이틀과 브레드크럼 --}}
            <div>

                {{-- 페이지 타이틀 --}}
                <h1 class="text-xl font-semibold text-gray-900" x-text="currentPage.title">페이지 1</h1>

                {{-- 브레드크럼 --}}
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <a href="/organizations" class="hover:text-gray-700">조직</a>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="/organizations/{{ request()->route('id') }}/projects" class="hover:text-gray-700">프로젝트</a>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-900 font-medium">프로젝트 대시보드</span>
                </nav>
            </div>

            {{-- 헤더 우측 메뉴 --}}
            <div class="flex items-center gap-4">
                {{-- Livewire 사용자 드롭다운 컴포넌트 --}}
                <livewire:service.header.user-dropdown-livewire />
            </div>
        </div>
    </div>
</div>
