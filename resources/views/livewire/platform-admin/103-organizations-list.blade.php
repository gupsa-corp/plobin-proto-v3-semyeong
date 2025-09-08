{{--
===========================================
개발 가이드라인 (DEVELOPMENT GUIDELINES)
===========================================

⚠️ 중요: 이 프로젝트에서는 순수 JavaScript 사용을 금지합니다
❌ 사용 금지: Vanilla JS, jQuery, Alpine.js의 복잡한 로직
✅ 사용 필수: Livewire + Filament 조합만 사용

모든 상호작용과 동적 기능은 다음으로만 구현:
- Livewire: 서버사이드 상태관리, 이벤트 처리
- Filament: UI 컴포넌트, 폼, 테이블 등
- 간단한 Alpine.js: 토글, 드롭다운 등 최소한의 UI 상호작용만

JavaScript가 필요한 경우 → Livewire로 재작성 필수
복잡한 UI가 필요한 경우 → Filament 컴포넌트 사용

===========================================
--}}

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


    {{-- 모달 컴포넌트 포함 --}}
    @include('livewire.platform-admin.101-modal-delete-organization')
    @include('livewire.platform-admin.100-modal-create-organization')
</div>
