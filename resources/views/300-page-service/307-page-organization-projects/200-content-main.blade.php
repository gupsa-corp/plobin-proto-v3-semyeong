{{-- 프로젝트 목록 및 생성 모달 Livewire 컴포넌트 --}}
<div>
    {{-- 프로젝트 목록 컴포넌트 --}}
    @livewire('organization.project-list', ['organizationId' => request()->route('id')])

    {{-- 프로젝트 생성 모달 컴포넌트 --}}
    @livewire('organization.create-project', ['organizationId' => request()->route('id')])
</div>