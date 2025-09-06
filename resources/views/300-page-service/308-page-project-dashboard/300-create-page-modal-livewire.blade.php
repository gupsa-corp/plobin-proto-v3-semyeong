{{-- 페이지 생성 모달 --}}
<div>
    {{-- 모달 열기 버튼 (사이드바에서 호출) --}}
    <button wire:click="toggleCreateModal" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; color: #9CA3AF; border: none; background: none; cursor: pointer;" type="button">
        <svg style="width: 16px; height: 16px;" viewBox="0 0 16 16" fill="none">
            <path d="M8 1V15M1 8H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
    </button>

    {{-- 모달 --}}
    @if($showCreateModal)
        <div x-data x-show="true" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 16px;"
             wire:click="toggleCreateModal">
            
            <div wire:click.stop 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 style="background: white; border-radius: 12px; padding: 24px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); position: relative;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0;">새 페이지 만들기</h3>
                    <button wire:click="toggleCreateModal" 
                            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; color: #6B7280; border: none; background: none; border-radius: 6px; cursor: pointer;"
                            onmouseover="this.style.background='#F3F4F6'"
                            onmouseout="this.style.background='none'">
                        <svg style="width: 20px; height: 20px;" viewBox="0 0 20 20" fill="none">
                            <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="createPage" x-data="{ title: @entangle('title') }">
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                            페이지 제목 <span style="color: #EF4444;">*</span>
                        </label>
                        <input wire:model="title" 
                               type="text" 
                               placeholder="페이지 제목을 입력하세요" 
                               style="width: 100%; padding: 12px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none;"
                               onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                               onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none'">
                        @error('title') 
                            <span style="color: #EF4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                            페이지 내용
                        </label>
                        <textarea wire:model="content" 
                                  rows="4" 
                                  placeholder="페이지 내용을 입력하세요 (선택사항)"
                                  style="width: 100%; padding: 12px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; resize: vertical; box-sizing: border-box; outline: none;"
                                  onfocus="this.style.borderColor='#3B82F6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                  onblur="this.style.borderColor='#D1D5DB'; this.style.boxShadow='none'"></textarea>
                        @error('content') 
                            <span style="color: #EF4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">
                            상태
                        </label>
                        <select wire:model="status" 
                                style="width: 100%; padding: 12px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 14px; box-sizing: border-box; outline: none;"
                                onfocus="this.style.borderColor='#3B82F6'"
                                onblur="this.style.borderColor='#D1D5DB'">
                            <option value="draft">임시저장</option>
                            <option value="published">공개</option>
                            <option value="archived">보관</option>
                        </select>
                        @error('status') 
                            <span style="color: #EF4444; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end;">
                        <button type="button" 
                                wire:click="toggleCreateModal"
                                style="padding: 12px 24px; border: 1px solid #D1D5DB; background: white; color: #6B7280; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer;"
                                onmouseover="this.style.background='#F9FAFB'"
                                onmouseout="this.style.background='white'">
                            취소
                        </button>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                :disabled="!title || title.trim() === ''"
                                style="padding: 12px 24px; background: #3B82F6; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer;" 
                                :class="{ 'opacity-50 cursor-not-allowed': !title || title.trim() === '' }"
                                @mouseenter="if(title && title.trim() !== '') $el.style.background='#2563EB'" 
                                @mouseleave="if(title && title.trim() !== '') $el.style.background='#3B82F6'">
                            @if($isLoading)
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 16px; height: 16px; border: 2px solid #ffffff; border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                                    생성 중...
                                </span>
                            @else
                                페이지 생성
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- 성공/에러 메시지 --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             style="position: fixed; top: 20px; right: 20px; background: #10B981; color: white; padding: 12px 20px; border-radius: 8px; z-index: 60;">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             style="position: fixed; top: 20px; right: 20px; background: #EF4444; color: white; padding: 12px 20px; border-radius: 8px; z-index: 60;">
            {{ session('error') }}
        </div>
    @endif
</div>