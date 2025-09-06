{{-- 조직 관리 페이지 헤더 --}}
<div class="bg-white border-b border-gray-200">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            {{-- 페이지 타이틀 섹션 --}}
            <div class="flex items-center gap-4">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">조직 관리</h1>
                    <p class="text-sm text-gray-500 mt-1">조직의 구성원, 권한, 결제 및 프로젝트를 관리합니다</p>
                </div>
            </div>

            {{-- 헤더 우측 메뉴 --}}
            <div class="flex items-center gap-4">
                {{-- Livewire 사용자 드롭다운 컴포넌트 --}}
                <livewire:service.header.user-dropdown-livewire />
            </div>
        </div>
    </div>
</div>
