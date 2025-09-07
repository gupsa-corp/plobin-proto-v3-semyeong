@extends('700-page-sandbox.700-common.301-layout-head')

@section('title', 'Git 버전 관리')

@section('content')
@include('700-page-sandbox.700-common.400-sandbox-header')

<div class="min-h-screen bg-gray-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <!-- 페이지 헤더 -->
        <div class="border-b border-gray-200 pb-5">
            <h1 class="text-3xl font-bold leading-6 text-gray-900">Git 버전 관리</h1>
            <p class="mt-2 max-w-4xl text-sm text-gray-500">
                샌드박스 환경의 Git 저장소를 초기화하고 버전 기록을 관리합니다.
            </p>
            <p class="mt-1 text-xs text-gray-400">
                작업 경로: /storage/storage-sandbox-1
            </p>
        </div>

        <!-- Git 상태 및 컨트롤 -->
        <div class="mt-8">
            @livewire('git-version-control')
        </div>
    </div>
</div>
@endsection