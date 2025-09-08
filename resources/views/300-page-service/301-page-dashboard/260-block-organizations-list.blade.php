<!-- 내 조직 목록 블록 -->
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900">내 조직</h3>
            <a href="/organizations" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                전체 보기
            </a>
        </div>
    </div>
    
    <div class="px-6 py-4">
        @if(isset($organizations) && $organizations->count() > 0)
            <div class="space-y-4">
                @foreach($organizations as $organization)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-1.654-.188-3.254-.599-4.75M7 20v-2c0-1.654.188-3.254.599-4.75M17 20v-2a3 3 0 00-3-3H9a3 3 0 00-3 3v2m8-16a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $organization->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        멤버 {{ $organization->members_count ?? 0 }}명
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('organization.dashboard', ['id' => $organization->id]) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                관리
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-1.654-.188-3.254-.599-4.75M7 20v-2c0-1.654.188-3.254.599-4.75M17 20v-2a3 3 0 00-3-3H9a3 3 0 00-3 3v2m8-16a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">조직이 없습니다</h3>
                <p class="mt-1 text-sm text-gray-500">새로운 조직을 만들거나 기존 조직에 참여해보세요.</p>
                <div class="mt-6">
                    <a href="/organizations/create" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        조직 만들기
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>