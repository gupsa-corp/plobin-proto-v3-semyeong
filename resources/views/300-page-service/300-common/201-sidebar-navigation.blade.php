<!-- 네비게이션 메뉴 -->
<div class="navigation-section" style="padding: 20px; flex: 1;">
    <ul class="nav-menu" style="list-style: none; padding: 0; margin: 0;">
        @foreach($navItems as $item)
        <li style="margin-bottom: 4px;">
            <a href="{{ $item['url'] }}" class="nav-item {{ $item['active'] ? 'active' : '' }}" style="display: flex; align-items: center; padding: 10px 12px; color: #111111; text-decoration: none; border-radius: 8px; transition: all 0.2s ease; font-size: 14px; {{ $item['active'] ? 'background: #E9E9ED;' : '' }}">
                <div class="nav-icon" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; margin-right: 8px; color: {{ $item['active'] ? '#0DC8AF' : '#666666' }};">
                    {!! $item['icon'] !!}
                </div>
                {{ $item['title'] }}
            </a>
        </li>
        @endforeach
    </ul>
</div>