<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '샌드박스 사용 프로젝트 목록'])

<body class="bg-gray-50">
    @include('700-page-sandbox.700-common.400-sandbox-header')

    <div class="container mx-auto px-4 py-8">
        <!-- 헤더 -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        📦 샌드박스 사용 프로젝트 목록
                    </h1>
                    <p class="text-gray-600 mt-2">현재 선택된 샌드박스 <span class="font-semibold text-blue-600">{{ $currentStorage }}</span>를 사용하고 있는 프로젝트 목록입니다.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('sandbox.storage-manager') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        샌드박스 관리
                    </a>
                    <a href="/sandbox" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        샌드박스로 이동
                    </a>
                </div>
            </div>
        </div>

        <!-- 프로젝트 목록 -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            @if(count($projects) > 0)
                <!-- 통계 -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">총 {{ count($projects) }}개 프로젝트가 이 샌드박스를 사용하고 있습니다</span>
                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                            <span class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                활성 연결
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 프로젝트 카드 목록 -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($projects as $project)
                            <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow bg-white">
                                <!-- 프로젝트 헤더 -->
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $project->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $project->organization_name }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        연결됨
                                    </span>
                                </div>

                                <!-- 프로젝트 설명 -->
                                @if($project->description)
                                    <p class="text-sm text-gray-700 mb-4 line-clamp-2">{{ $project->description }}</p>
                                @endif

                                <!-- 메타 정보 -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        연결일: {{ \Carbon\Carbon::parse($project->sandbox_assigned_at)->format('Y-m-d H:i') }}
                                    </div>
                                    @if($project->sandbox_updated_at !== $project->sandbox_assigned_at)
                                        <div class="flex items-center text-xs text-gray-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            최종 수정: {{ \Carbon\Carbon::parse($project->sandbox_updated_at)->format('Y-m-d H:i') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- 액션 버튼 -->
                                <div class="flex space-x-2">
                                    <a href="/organizations/{{ $project->organization_id }}/projects/{{ $project->id }}/pages" 
                                       class="flex-1 text-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors">
                                        프로젝트 보기
                                    </a>
                                    <a href="/organizations/{{ $project->organization_id }}/projects/{{ $project->id }}/settings/sandboxes" 
                                       class="flex-1 text-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors">
                                        설정 관리
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- 빈 상태 -->
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">사용 중인 프로젝트가 없습니다</h3>
                    <p class="text-gray-500 mb-6">현재 선택된 샌드박스 <strong>{{ $currentStorage }}</strong>를 사용하고 있는 프로젝트가 없습니다.</p>
                    <div class="flex justify-center space-x-4">
                        <a href="/organizations" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            프로젝트 관리하러 가기
                        </a>
                        <a href="{{ route('sandbox.storage-manager') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            다른 샌드박스 선택
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Filament Scripts -->
    @filamentScripts
</body>
</html>