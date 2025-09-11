<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '조직 설정 - 사용자 관리'])
<body class="bg-gray-100">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="min-h-screen" style="position: relative;">
        @include('800-page-organization-admin.800-common.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('800-page-organization-admin.800-common.100-header-main')
            
            <!-- 페이지 헤더 -->
            <div class="bg-white border-b border-gray-200 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">사용자 관리</h1>
                        <p class="text-sm text-gray-600 mt-1">조직 멤버를 초대하고 관리하세요</p>
                    </div>
                    <button id="inviteUsersBtn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        사용자 초대
                    </button>
                </div>
            </div>

            <!-- 메인 콘텐츠 -->
            <div class="p-8">
                <!-- 멤버 목록 -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">조직 멤버 ({{ $members->count() }}명)</h3>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @forelse($members as $member)
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ strtoupper(substr($member->user->name ?? $member->user->email, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $member->user->name ?? '이름 없음' }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->user->email }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-sm text-gray-500">
                                        @switch($member->invitation_status)
                                            @case('pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">초대 대기</span>
                                                @break
                                            @case('accepted')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">참여</span>
                                                @break
                                            @case('declined')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">거절</span>
                                                @break
                                        @endswitch
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $member->getRoleDisplayName() }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $member->created_at->format('Y.m.d') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">조직에 멤버가 없습니다</p>
                                    <p class="text-sm text-gray-400">새 멤버를 초대해보세요</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 사용자 초대 모달 -->
    <div id="inviteModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                사용자 초대
                            </h3>
                            <form id="inviteForm">
                                <div class="space-y-4">
                                    <div id="inviteFields">
                                        <div class="invite-field flex space-x-2">
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700">이메일 주소</label>
                                                <input type="email" name="emails[]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="user@example.com" required>
                                            </div>
                                            <div class="w-32">
                                                <label class="block text-sm font-medium text-gray-700">역할</label>
                                                <select name="roles[]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                    <option value="user">사용자</option>
                                                    <option value="service_manager">서비스 매니저</option>
                                                    <option value="organization_admin">조직 관리자</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="addFieldBtn" class="text-sm text-indigo-600 hover:text-indigo-500">+ 다른 사용자 추가</button>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">초대 메시지 (선택사항)</label>
                                        <textarea name="message" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="초대 메시지를 입력하세요"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="sendInviteBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        초대하기
                    </button>
                    <button type="button" id="cancelBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        취소
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 결과 모달 -->
    <div id="resultModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="result-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div id="resultContent"></div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="closeResultBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        확인
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inviteModal = document.getElementById('inviteModal');
            const resultModal = document.getElementById('resultModal');
            const inviteBtn = document.getElementById('inviteUsersBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const sendInviteBtn = document.getElementById('sendInviteBtn');
            const closeResultBtn = document.getElementById('closeResultBtn');
            const addFieldBtn = document.getElementById('addFieldBtn');
            const inviteFields = document.getElementById('inviteFields');
            
            // 모달 열기
            inviteBtn.addEventListener('click', function() {
                inviteModal.classList.remove('hidden');
            });
            
            // 모달 닫기
            cancelBtn.addEventListener('click', function() {
                inviteModal.classList.add('hidden');
                resetForm();
            });
            
            closeResultBtn.addEventListener('click', function() {
                resultModal.classList.add('hidden');
            });
            
            // 필드 추가
            addFieldBtn.addEventListener('click', function() {
                const newField = document.createElement('div');
                newField.className = 'invite-field flex space-x-2 mt-2';
                newField.innerHTML = `
                    <div class="flex-1">
                        <input type="email" name="emails[]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="user@example.com" required>
                    </div>
                    <div class="w-32">
                        <select name="roles[]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="user">사용자</option>
                            <option value="service_manager">서비스 매니저</option>
                            <option value="organization_admin">조직 관리자</option>
                        </select>
                    </div>
                    <button type="button" class="remove-field text-red-600 hover:text-red-500 p-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                `;
                
                // 삭제 버튼 이벤트 리스너
                const removeBtn = newField.querySelector('.remove-field');
                removeBtn.addEventListener('click', function() {
                    newField.remove();
                });
                
                inviteFields.appendChild(newField);
            });
            
            // 초대 전송
            sendInviteBtn.addEventListener('click', function() {
                const form = document.getElementById('inviteForm');
                const formData = new FormData(form);
                
                const emails = formData.getAll('emails[]').filter(email => email.trim() !== '');
                const roles = formData.getAll('roles[]');
                const message = formData.get('message');
                
                if (emails.length === 0) {
                    alert('최소 하나의 이메일을 입력해주세요.');
                    return;
                }
                
                const invitations = emails.map((email, index) => ({
                    email: email.trim(),
                    role: roles[index] || 'user',
                    message: message
                }));
                
                // 버튼 비활성화
                sendInviteBtn.disabled = true;
                sendInviteBtn.textContent = '초대 중...';
                
                // API 호출
                fetch(`/api/organizations/{{ $id }}/members/invite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ invitations: invitations })
                })
                .then(response => response.json())
                .then(data => {
                    inviteModal.classList.add('hidden');
                    showResult(data);
                    resetForm();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('초대 처리 중 오류가 발생했습니다.');
                })
                .finally(() => {
                    sendInviteBtn.disabled = false;
                    sendInviteBtn.textContent = '초대하기';
                });
            });
            
            function resetForm() {
                document.getElementById('inviteForm').reset();
                const extraFields = document.querySelectorAll('.invite-field:not(:first-child)');
                extraFields.forEach(field => field.remove());
            }
            
            function showResult(data) {
                const resultContent = document.getElementById('resultContent');
                let html = '<h3 class="text-lg font-medium text-gray-900 mb-4">초대 결과</h3>';
                
                if (data.data && data.data.results) {
                    const results = data.data.results;
                    
                    if (results.successful && results.successful.length > 0) {
                        html += '<div class="mb-4"><h4 class="text-sm font-medium text-green-800 mb-2">성공적으로 초대됨 (' + results.successful.length + '명)</h4>';
                        html += '<ul class="text-sm text-green-700 space-y-1">';
                        results.successful.forEach(item => {
                            html += '<li>• ' + item.name + ' (' + item.email + ') - ' + item.role.label + '</li>';
                        });
                        html += '</ul></div>';
                    }
                    
                    if (results.already_exists && results.already_exists.length > 0) {
                        html += '<div class="mb-4"><h4 class="text-sm font-medium text-yellow-800 mb-2">이미 존재하는 멤버 (' + results.already_exists.length + '명)</h4>';
                        html += '<ul class="text-sm text-yellow-700 space-y-1">';
                        results.already_exists.forEach(item => {
                            html += '<li>• ' + item.name + ' (' + item.email + ') - ' + item.reason + '</li>';
                        });
                        html += '</ul></div>';
                    }
                    
                    if (results.failed && results.failed.length > 0) {
                        html += '<div class="mb-4"><h4 class="text-sm font-medium text-red-800 mb-2">실패 (' + results.failed.length + '명)</h4>';
                        html += '<ul class="text-sm text-red-700 space-y-1">';
                        results.failed.forEach(item => {
                            html += '<li>• ' + item.email + ' - ' + item.reason + '</li>';
                        });
                        html += '</ul></div>';
                    }
                } else {
                    html += '<p class="text-sm text-gray-600">' + (data.message || '초대가 완료되었습니다.') + '</p>';
                }
                
                resultContent.innerHTML = html;
                resultModal.classList.remove('hidden');
                
                // 성공한 경우 페이지 새로고침
                if (data.data && data.data.successful_count > 0) {
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            }
        });
    </script>
</body>
</html>