{{-- 시스템 설정 페이지 --}}
<main class="flex-1 p-6">
    {{-- 페이지 헤더 --}}
    {!! renderComponent('100-basic-elements', 'page_header', [
        'title' => '시스템 설정',
        'description' => '시스템 전반적인 설정을 관리합니다.'
    ]) !!}
    
    {{-- 설정 카드들 --}}
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- 기본 설정 --}}
        <div class="admin-card p-6">
            {!! renderComponent('100-basic-elements', 'card_header', [
                'title' => '기본 설정'
            ]) !!}
            
            <div class="mt-4 space-y-4">
                {!! renderComponent('200-forms', 'admin_settings_form') !!}
            </div>
        </div>
        
        {{-- 보안 설정 --}}
        <div class="admin-card p-6">
            {!! renderComponent('100-basic-elements', 'card_header', [
                'title' => '보안 설정'
            ]) !!}
            
            <div class="mt-4 space-y-4">
                {!! renderComponent('200-forms', 'security_settings_form') !!}
            </div>
        </div>
    </div>
</main>