{{-- 조직 목록 및 생성 모달 Livewire 컴포넌트 --}}
<div>
    {{-- 조직 목록 컴포넌트 --}}
    @livewire('organization.organization-list')

    {{-- 조직 생성 모달 컴포넌트 --}}
    @livewire('organization.create-organization')
</div>