<div class="p-6" x-data="profileEditPage()">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">개인정보 수정</h1>
        <p class="text-gray-600 mt-1">계정의 개인정보를 수정할 수 있습니다.</p>
        <div class="mt-2">
            <a href="/mypage" class="text-blue-600 hover:text-blue-700 text-sm">← 프로필로 돌아가기</a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 개인정보 수정 폼 -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">기본 정보 수정</h3>
                </div>
                <div class="p-6">
                    <form id="profile-edit-form" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">이름 <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="name" name="name" value="홍길동" required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">이메일</label>
                            <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="email" name="email" value="user@example.com" readonly>
                            <p class="text-xs text-gray-500 mt-1">이메일은 변경할 수 없습니다.</p>
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">연락처</label>
                            <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="phone" name="phone" value="010-1234-5678" placeholder="010-0000-0000">
                        </div>
                        
                        <div>
                            <label for="organization" class="block text-sm font-medium text-gray-700 mb-2">소속</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="organization" name="organization" value="샘플 조직" readonly>
                            <p class="text-xs text-gray-500 mt-1">소속은 조직 관리에서 변경할 수 있습니다.</p>
                        </div>
                        
                        <div class="pt-4 flex space-x-3">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">저장</button>
                            <a href="/profile" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">취소</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- 비밀번호 변경 -->
        <div>
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">비밀번호 변경</h3>
                </div>
                <div class="p-6">
                    <form id="password-change-form" class="space-y-4">
                        <div>
                            <label for="current-password" class="block text-sm font-medium text-gray-700 mb-2">현재 비밀번호 <span class="text-red-500">*</span></label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="current-password" name="current_password" required>
                        </div>
                        
                        <div>
                            <label for="new-password" class="block text-sm font-medium text-gray-700 mb-2">새 비밀번호 <span class="text-red-500">*</span></label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="new-password" name="new_password" required>
                            <p class="text-xs text-gray-500 mt-1">최소 8자 이상, 영문, 숫자, 특수문자 조합</p>
                        </div>
                        
                        <div>
                            <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-2">비밀번호 확인 <span class="text-red-500">*</span></label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="confirm-password" name="new_password_confirmation" required>
                        </div>
                        
                        <div class="pt-2">
                            <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">비밀번호 변경</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>