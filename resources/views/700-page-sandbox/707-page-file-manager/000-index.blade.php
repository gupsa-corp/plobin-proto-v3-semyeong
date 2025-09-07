@extends('300-common.302-layout-app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white shadow-lg rounded-lg">
                <div class="border-b px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">파일 매니저</h2>
                    <p class="text-sm text-gray-600 mt-1">파일 업로드, 관리, 다운로드를 위한 통합 파일 시스템</p>
                </div>
                
                <div class="p-6">
                    <x-livewire-filemanager />
                </div>
            </div>
        </div>
    </div>
@endsection