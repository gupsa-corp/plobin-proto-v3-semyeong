<!-- 알림 메시지 -->
@if (session()->has('message'))
    <div class="border px-4 py-3 rounded bg-blue-100 border-blue-400 text-blue-700">
        {{ session('message') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="border px-4 py-3 rounded bg-red-100 border-red-400 text-red-700">
        {{ session('error') }}
    </div>
@endif