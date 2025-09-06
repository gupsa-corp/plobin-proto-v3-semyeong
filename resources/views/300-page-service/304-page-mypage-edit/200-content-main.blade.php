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
            @livewire('profile.edit-profile')
        </div>
        
        <!-- 비밀번호 변경 -->
        <div>
            @livewire('profile.change-password')
        </div>
    </div>
</div>