{{-- 공통 서비스 페이지 헤더 --}}
<div class="bg-white border-b border-gray-200">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            {{-- 페이지 타이틀 --}}
            <div>
                <h1 class="text-xl font-semibold text-gray-900">{{ $pageTitle ?? '페이지' }}</h1>
            </div>
            
            {{-- 헤더 우측 메뉴 --}}
            <div class="flex items-center gap-4">
                {{-- Livewire 사용자 드롭다운 컴포넌트 --}}
                <livewire:service.header.user-dropdown-livewire />
            </div>
        </div>
    </div>
</div>