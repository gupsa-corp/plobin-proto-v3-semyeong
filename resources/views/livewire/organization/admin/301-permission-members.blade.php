{{-- 멤버 권한 Livewire 컴포넌트 뷰 --}}
<div class="permission-members-container" style="padding: 24px;" x-data="organizationPermissionMembers">

    {{-- 탭 네비게이션 --}}
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('organization.admin.permissions.overview', ['id' => $organizationId]) }}"
                   class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    권한 개요
                </a>
                <a href="{{ route('organization.admin.permissions.roles', ['id' => $organizationId]) }}"
                   class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    역할 관리
                </a>
                <a href="{{ route('organization.admin.permissions.management', ['id' => $organizationId]) }}"
                   class="whitespace-nowrap py-2 px-1 border-b-2 border-blue-500 text-blue-600 font-medium text-sm">
                    멤버 권한
                </a>
            </nav>
        </div>
    </div>

    {{-- 멤버 권한 탭 --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">멤버 권한 관리</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">멤버</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">현재 역할</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">가입일</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">액션</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($members as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-gray-700">{{ substr($member['name'], 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $member['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $member['email'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $member['primary_role'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span>{{ $member['joined_at'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="showRoleChangeModal({{ $member['id'] }}, '{{ $member['name'] }}', '{{ $member['primary_role'] }}')"
                                        class="text-blue-600 hover:text-blue-900">역할 변경</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- 역할 변경 모달 --}}
    <div x-show="showRoleChangeModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showRoleChangeModal = false"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">멤버 역할 변경</h3>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-600" x-text="'멤버: ' + selectedMember.name"></p>
                        <p class="text-sm text-gray-600" x-text="'현재 역할: ' + selectedMember.currentRole"></p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">새 역할</label>
                        <select x-model="selectedMember.newRole" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">역할을 선택하세요</option>
                            @foreach($roles as $role)
                                <option value="{{ $role['name'] }}">{{ $role['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="confirmRoleChange()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        변경
                    </button>
                    <button type="button" 
                            @click="showRoleChangeModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        취소
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('organizationPermissionMembers', () => ({
        showRoleChangeModal: false,
        selectedMember: {
            id: null,
            name: '',
            currentRole: '',
            newRole: ''
        },

        init() {
            console.log('Organization permission members initialized');
        },

        showRoleChangeDialog(memberId, memberName, currentRole) {
            this.selectedMember = {
                id: memberId,
                name: memberName,
                currentRole: currentRole,
                newRole: ''
            };
            this.showRoleChangeModal = true;
        },

        confirmRoleChange() {
            if (!this.selectedMember.newRole) {
                alert('새 역할을 선택해주세요.');
                return;
            }

            @this.call('changeMemberRole', this.selectedMember.id, this.selectedMember.newRole)
                .then(() => {
                    this.showRoleChangeModal = false;
                    location.reload(); // 페이지 새로고침
                })
                .catch(error => {
                    alert('역할 변경 중 오류가 발생했습니다.');
                    console.error(error);
                });
        }
    }));
});

// 전역 함수로 모달 호출
function showRoleChangeModal(memberId, memberName, currentRole) {
    const component = Alpine.$data(document.querySelector('[x-data="organizationPermissionMembers"]'));
    component.showRoleChangeDialog(memberId, memberName, currentRole);
}
</script>