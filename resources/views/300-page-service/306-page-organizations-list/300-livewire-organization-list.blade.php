{{-- 조직 목록 Livewire 컴포넌트 --}}
<div class="organizations-list-content" style="padding: 24px;">
    {{-- 헤더 --}}
    <div class="flex justify-between items-center mb-8">
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">조직 관리</h1>
            <p class="text-lg text-gray-500">소속된 조직을 관리하고 새로운 조직을 생성할 수 있습니다.</p>
        </div>
        <button wire:click="$dispatch('openCreateModal')" class="flex items-center justify-center gap-2 px-6 py-3 bg-teal-500 hover:bg-teal-600 text-white font-bold text-sm rounded-lg">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M15.625 9.375H10.625V4.375C10.625 4.02982 10.3452 3.75 10 3.75C9.65482 3.75 9.375 4.02982 9.375 4.375V9.375H4.375C4.02982 9.375 3.75 9.65482 3.75 10C3.75 10.3452 4.02982 10.625 4.375 10.625H9.375V15.625C9.375 15.9702 9.65482 16.25 10 16.25C10.3452 16.25 10.625 15.9702 10.625 15.625V10.625H15.625C15.9702 10.625 16.25 10.3452 16.25 10C16.25 9.65482 15.9702 9.375 15.625 9.375Z" fill="white"/>
            </svg>
            새 조직 생성
        </button>
    </div>

    {{-- 조직 목록 --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        {{-- 목록 헤더 --}}
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-700">
                <div class="col-span-4">조직명</div>
                <div class="col-span-3">멤버 수</div>
                <div class="col-span-2">생성일</div>
                <div class="col-span-2">상태</div>
                <div class="col-span-1">관리</div>
            </div>
        </div>

        {{-- 조직 목록 내용 --}}
        <div class="divide-y divide-gray-200">
            @if($isLoading)
                {{-- 로딩 상태 --}}
                <div class="px-6 py-12 text-center">
                    <div class="inline-flex items-center gap-2 text-gray-500">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        조직 목록을 불러오는 중...
                    </div>
                </div>
            @elseif(count($organizations) === 0)
                {{-- 빈 상태 --}}
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">아직 조직이 없습니다</h3>
                        <p class="text-gray-500 mb-4">새로운 조직을 생성해서 시작해보세요.</p>
                        <button wire:click="$dispatch('openCreateModal')" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white font-medium rounded-lg">
                            첫 번째 조직 생성하기
                        </button>
                    </div>
                </div>
            @else
                {{-- 조직 목록 아이템들 --}}
                @foreach($organizations as $org)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-4">
                                <h3 class="font-medium text-gray-900">{{ $org->name ?? '이름 없음' }}</h3>
                                <p class="text-sm text-gray-500">{{ $org->description ?? '설명 없음' }}</p>
                            </div>
                            <div class="col-span-3 text-sm text-gray-600">{{ $org->members_count ?? 0 }}명</div>
                            <div class="col-span-2 text-sm text-gray-600">{{ $org->created_at ? $org->created_at->format('Y-m-d') : '-' }}</div>
                            <div class="col-span-2">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $org->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $org->status === 'active' ? '활성' : '비활성' }}
                                </span>
                            </div>
                            <div class="col-span-1">
                                <button class="text-teal-600 hover:text-teal-900 text-sm font-medium">관리</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>