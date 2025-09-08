{{-- 로고 컴포넌트 --}}
<div class="sidebar-logo" style="padding: 20px; border-bottom: 1px solid #E1E1E4;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <a href="/dashboard" style="display: flex; align-items: center; text-decoration: none; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
            {!! file_get_contents(public_path('icons/logo_plobin.svg')) !!}
        </a>
    </div>
</div>
