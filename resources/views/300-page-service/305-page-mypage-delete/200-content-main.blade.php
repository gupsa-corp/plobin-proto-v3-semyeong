<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-red-600">회원탈퇴</h1>
        <p class="text-gray-600 mt-1">계정을 영구적으로 삭제합니다. 이 작업은 되돌릴 수 없습니다.</p>
        <div class="mt-2">
            <a href="/profile" class="text-blue-600 hover:text-blue-700 text-sm">← 프로필로 돌아가기</a>
        </div>
    </div>
    
    <!-- 경고 섹션 -->
    <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">주의사항</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>회원탈퇴를 진행하면 다음과 같은 데이터가 영구적으로 삭제됩니다:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>계정 정보 및 프로필 데이터</li>
                        <li>소속된 조직의 멤버십 정보</li>
                        <li>서비스 이용 기록 및 설정</li>
                        <li>저장된 모든 개인 데이터</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 현재 계정 정보 -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">삭제될 계정 정보</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">이름</label>
                    <p class="text-base text-gray-900">{{ $user->name ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">이메일</label>
                    <p class="text-base text-gray-900">{{ $user->email ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">가입일</label>
                    <p class="text-base text-gray-900">{{ $user->created_at ? $user->created_at->format('Y-m-d') : '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">소속 조직</label>
                    <p class="text-base text-gray-900">
                        @if($organizations->isNotEmpty())
                            {{ $organizations->count() }}개 조직
                        @else
                            없음
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($ownedOrganizations->isNotEmpty())
    <!-- 조직 소유자 경고 -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">회원 탈퇴 불가</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>다음 조직의 소유자이므로 회원 탈퇴를 할 수 없습니다:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        @foreach($ownedOrganizations as $org)
                        <li><strong>{{ $org->name }}</strong></li>
                        @endforeach
                    </ul>
                    <p class="mt-3 font-medium">회원 탈퇴를 원하시면 다음 중 하나를 선택하세요:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>조직을 다른 멤버에게 양도</li>
                        <li>조직을 완전 삭제</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($organizations->isNotEmpty() && $ownedOrganizations->isEmpty())
    <!-- 조직 멤버 정보 -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-6 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">조직 멤버십 정보</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>현재 다음 조직의 멤버입니다:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        @foreach($organizations as $org)
                        @php $member = $org->members->first(); @endphp
                        <li><strong>{{ $org->name }}</strong> ({{ $member->role_name ?? 'member' }})</li>
                        @endforeach
                    </ul>
                    <p class="mt-3">회원 탈퇴 시 이 모든 조직에서 자동으로 제외됩니다.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- 탈퇴 처리 폼 -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-red-600">회원탈퇴 확인</h3>
        </div>
        <div class="p-6">
            @if($ownedOrganizations->isNotEmpty())
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <h4 class="text-lg font-medium text-gray-900 mb-2">회원 탈퇴가 제한되었습니다</h4>
                <p class="text-gray-600">소유하고 있는 조직을 먼저 처리해주세요.</p>
            </div>
            @else
            <form id="account-delete-form" class="space-y-6">
                <!-- 탈퇴 사유 -->
                <div>
                    <label for="delete-reason" class="block text-sm font-medium text-gray-700 mb-2">탈퇴 사유 <span class="text-red-500">*</span></label>
                    <select id="delete-reason" name="reason" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" required>
                        <option value="">사유를 선택해주세요</option>
                        <option value="not-using">서비스를 더 이상 이용하지 않음</option>
                        <option value="privacy">개인정보 보호 우려</option>
                        <option value="functionality">기능이 만족스럽지 않음</option>
                        <option value="alternative">다른 서비스 이용</option>
                        <option value="other">기타</option>
                    </select>
                </div>
                
                <!-- 기타 사유 입력 -->
                <div id="other-reason-section" class="hidden">
                    <label for="other-reason" class="block text-sm font-medium text-gray-700 mb-2">기타 사유</label>
                    <textarea id="other-reason" name="other_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" placeholder="탈퇴 사유를 상세히 입력해주세요"></textarea>
                </div>
                
                <!-- 비밀번호 확인 -->
                <div>
                    <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-2">비밀번호 확인 <span class="text-red-500">*</span></label>
                    <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" id="password-confirm" name="password" placeholder="현재 비밀번호를 입력하세요" required>
                    <p class="text-xs text-gray-500 mt-1">본인 확인을 위해 현재 비밀번호를 입력해주세요.</p>
                </div>
                
                <!-- 확인 체크박스 -->
                <div class="space-y-3">
                    <div class="flex items-start">
                        <input type="checkbox" id="confirm-understand" class="mt-0.5 h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500" required>
                        <label for="confirm-understand" class="ml-2 text-sm text-gray-700">위 주의사항을 모두 확인했으며, 회원탈퇴 시 모든 데이터가 영구적으로 삭제됨을 이해합니다.</label>
                    </div>
                    
                    <div class="flex items-start">
                        <input type="checkbox" id="confirm-final" class="mt-0.5 h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500" required>
                        <label for="confirm-final" class="ml-2 text-sm text-gray-700">회원탈퇴를 최종 확인하며, 이 작업은 되돌릴 수 없음을 인지합니다.</label>
                    </div>
                </div>
                
                <!-- 확인 텍스트 입력 -->
                <div>
                    <label for="delete-confirmation-text" class="block text-sm font-medium text-gray-700 mb-2">확인 텍스트 <span class="text-red-500">*</span></label>
                    <input type="text" id="delete-confirmation-text" name="confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" placeholder="계정삭제" required>
                    <p class="text-xs text-gray-500 mt-1">확인을 위해 <strong>"계정삭제"</strong>를 정확히 입력해주세요.</p>
                </div>
                
                <!-- 버튼 -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <a href="/profile" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                        취소
                    </a>
                    <button type="submit" id="delete-submit-btn" class="bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 opacity-50 cursor-not-allowed" disabled>
                        계정 영구 삭제
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>