{{-- 사용자 관리 페이지 --}}
<main class="flex-1 p-6">
    {{-- 페이지 헤더 --}}
    {!! renderComponent('100-basic-elements', 'page_header', [
        'title' => '사용자 관리',
        'description' => '시스템 사용자를 관리합니다.'
    ]) !!}
    
    {{-- 사용자 목록 테이블 --}}
    <div class="mt-6 admin-card p-6">
        {!! renderComponent('500-tables', 'admin_user_table') !!}
    </div>
</main>