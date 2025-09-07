<div class="organizations-content" style="padding: 24px;">

    {{-- 조직 목록 테이블 --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">전체 조직 목록</h3>
            <button
                wire:click="openCreateModal"
                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                + 새 조직 추가
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            조직 정보
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            소유 관리자
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            멤버
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            프로젝트
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            생성일
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            액션
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($organizations as $org)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ $org->name ? substr($org->name, 0, 1) : 'N' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $org->name ?: 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">ID : {{ $org->id ?: 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $org->owner ? $org->owner->email : 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            ID: {{ $org->user_id ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $org->members_count ?? 0 }} 멤버
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    {{ $org->projects_count ?? 0 }} 프로젝트
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $org->created_at ? $org->created_at->format('Y-m-d') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center space-x-2">
                                    <a href="/organizations/{{ $org->id }}/dashboard"
                                       class="text-blue-600 hover:text-blue-900">보기</a>
                                    <span class="text-gray-300">|</span>
                                    <button
                                        wire:click="confirmDelete({{ $org->id }})"
                                        class="text-red-600 hover:text-red-900">
                                        삭제
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                @if($search)
                                    "{{ $search }}"에 대한 검색 결과가 없습니다.
                                @else
                                    등록된 조직이 없습니다.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 페이지네이션 --}}
        @if($organizations->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $organizations->links() }}
            </div>
        @endif
    </div>

    {{-- 삭제 확인 모달 --}}
    @if($confirmingDelete)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-5">조직 삭제</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            정말로 이 조직을 삭제하시겠습니까?<br>
                            이 작업은 되돌릴 수 없습니다.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-3 mt-5">
                        <button
                            wire:click="cancelDelete"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            취소
                        </button>
                        <button
                            wire:click="deleteOrganization"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            삭제
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 조직 생성 모달 --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-5 text-center">새 조직 추가</h3>

                    <form wire:submit.prevent="createOrganization" class="mt-5">
                        <div class="space-y-4">
                            {{-- 조직 소유자 선택 --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">조직 소유자 *</label>

                                @if($selectedUser)
                                    {{-- 선택된 사용자 표시 --}}
                                    <div class="mt-1 flex items-center justify-between p-3 border border-green-300 bg-green-50 rounded-md">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-green-500 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">
                                                    {{ substr($selectedUser->display_name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $selectedUser->display_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $selectedUser->email }}</p>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            wire:click="clearSelectedUser"
                                            class="text-gray-400 hover:text-gray-600">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    {{-- 사용자 검색 --}}
                                    <div class="mt-1 relative" x-data="userSearch()">
                                        <input
                                            type="text"
                                            x-model="query"
                                            @input.debounce.300ms="search()"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="사용자 이름 또는 이메일로 검색..."
                                        >

                                        <div x-show="results.length > 0" x-cloak class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                            <template x-for="user in results" :key="user.id">
                                                <div
                                                    @click="selectUser(user)"
                                                    class="flex items-center p-3 hover:bg-gray-50 cursor-pointer">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700" x-text="user.display_name.charAt(0)"></span>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900" x-text="user.display_name"></p>
                                                        <p class="text-xs text-gray-500" x-text="user.email"></p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                @endif

                                @error('selectedUser')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 조직명 --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">조직명 *</label>
                                <input
                                    type="text"
                                    id="name"
                                    wire:model="name"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="조직명을 입력하세요"
                                    maxlength="25"
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- URL --}}
                            <div>
                                <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                                <input
                                    type="text"
                                    id="url"
                                    wire:model="url"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="organization-url"
                                    maxlength="50"
                                >
                                @error('url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 설명 --}}
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">설명</label>
                                <textarea
                                    id="description"
                                    wire:model="description"
                                    rows="3"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="조직에 대한 설명을 입력하세요">
                                </textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button
                                type="button"
                                wire:click="closeCreateModal"
                                class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                취소
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                조직 생성
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function userSearch() {
    return {
        query: '',
        results: [],
        loading: false,

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                return;
            }

            this.loading = true;

            try {
                const response = await fetch(`/api/users/search?q=${encodeURIComponent(this.query)}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    this.results = data.data.users || [];
                } else {
                    throw new Error(data.message || '사용자 검색에 실패했습니다.');
                }
            } catch (error) {
                console.error('사용자 검색 에러:', error);
                this.results = [];
            } finally {
                this.loading = false;
            }
        },

        selectUser(user) {
            // Livewire의 selectedUser를 설정
            @this.set('selectedUser', user);
            @this.set('showUserSearch', false);

            // 검색 결과 초기화
            this.query = '';
            this.results = [];
        }
    }
}
</script>
