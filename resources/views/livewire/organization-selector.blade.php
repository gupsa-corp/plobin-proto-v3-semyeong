<div class="organization-section" style="padding: 16px 20px; position: relative;">
    <div class="org-selector" wire:click="toggleDropdown" style="display: flex; align-items: center; padding: 10px 12px; background: #E9E9ED; border: 0.5px solid #E1E1E4; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
        <div class="org-icon" style="width: 28px; height: 28px; background: #ffffff; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 8px;">
            <div class="chart-icon" style="width: 20px; height: 20px; background: #0DC8AF; border-radius: 2px; position: relative;"></div>
        </div>
        <span class="org-text" style="flex: 1; font-size: 14px; color: #666666;">
            {{ $this->currentOrganization ? $this->currentOrganization->name : '조직을 선택해주세요' }}
        </span>
        <svg class="dropdown-arrow" width="12" height="12" viewBox="0 0 12 12" 
             style="color: #666666; transition: transform 0.2s ease; transform: {{ $isDropdownOpen ? 'rotate(180deg)' : 'rotate(0deg)' }};">
            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" fill="none" stroke-width="1.5"/>
        </svg>
    </div>

    @if($isDropdownOpen)
        <div class="org-dropdown" style="position: absolute; top: 100%; left: 20px; right: 20px; background: #ffffff; border: 1px solid #E1E1E4; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); z-index: 50;">
            <div class="org-dropdown-header" style="padding: 12px; border-bottom: 1px solid #E1E1E4;">
                <input type="text" wire:model.live="searchTerm" placeholder="조직 검색..." 
                       style="width: 100%; padding: 8px 12px; border: 1px solid #E1E1E4; border-radius: 6px; font-size: 14px; outline: none; box-sizing: border-box;">
            </div>
            
            <div class="org-list" style="max-height: 200px; overflow-y: auto;">
                @if($this->filteredOrganizations->count() === 0)
                    <div class="no-org-message" style="padding: 20px; text-align: center; color: #666666; font-size: 14px;">
                        @if($organizations->count() === 0)
                            조직이 없습니다<br>
                            <span style="font-size: 12px; color: #888888;">새 조직을 만들어 시작해보세요</span>
                        @else
                            검색 결과가 없습니다
                        @endif
                    </div>
                @else
                    @foreach($this->filteredOrganizations as $org)
                        <div wire:click="selectOrganization({{ $org->id }})" 
                             class="org-item" 
                             style="display: flex; align-items: center; padding: 12px; cursor: pointer; border-bottom: 1px solid #F3F4F6; transition: background 0.2s ease;"
                             onmouseover="this.style.background='#F9FAFB'" 
                             onmouseout="this.style.background='transparent'">
                            <div class="org-icon" style="width: 32px; height: 32px; background: #ffffff; border: 1px solid #E1E1E4; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <div class="chart-icon" style="width: 20px; height: 20px; background: #0DC8AF; border-radius: 2px; position: relative;"></div>
                            </div>
                            <div class="org-info" style="flex: 1;">
                                <div class="org-name" style="font-size: 14px; font-weight: 500; color: #111111; margin-bottom: 2px;">{{ $org->name }}</div>
                                <div class="org-desc" style="font-size: 12px; color: #666666;">조직 ID: {{ $org->id }}</div>
                            </div>
                            @if($org->id === $currentOrgId)
                                <div class="check-icon" style="color: #0DC8AF;">
                                    <svg width="16" height="16" viewBox="0 0 16 16">
                                        <path d="M13.5 4.5L6 12L2.5 8.5" stroke="currentColor" fill="none" stroke-width="2"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
            
            <div class="org-actions" style="padding: 12px; border-top: 1px solid #E1E1E4;">
                <button wire:click="createOrganization" 
                        style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 8px 12px; background: transparent; border: 1px solid #E1E1E4; border-radius: 6px; font-size: 12px; color: #111111; cursor: pointer; transition: all 0.2s ease;"
                        onmouseover="this.style.background='#F3F4F6'; this.style.borderColor='#D1D5DB'"
                        onmouseout="this.style.background='transparent'; this.style.borderColor='#E1E1E4'">
                    <svg width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 3v10M3 8h10" stroke="currentColor" fill="none" stroke-width="1.5"/>
                    </svg>
                    조직 만들기
                </button>
            </div>
        </div>
    @endif

    {{-- 외부 클릭시 드롭다운 닫기 --}}
    @if($isDropdownOpen)
        <div wire:click="toggleDropdown" style="position: fixed; inset: 0; z-index: 40;"></div>
    @endif
</div>