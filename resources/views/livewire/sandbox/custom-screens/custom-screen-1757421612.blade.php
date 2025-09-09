<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $title }}</h1>

    @if($users)
        <div class="space-y-4">
            @foreach($users as $user)
                <div class="border rounded p-4">
                    <h3 class="font-semibold">{{ $user['name'] }}</h3>
                    <p class="text-gray-600">{{ $user['email'] }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">데이터가 없습니다.</p>
    @endif
</div>