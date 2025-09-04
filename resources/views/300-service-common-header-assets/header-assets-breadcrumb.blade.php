<!-- 브레드크럼 -->
<nav class="flex items-center space-x-2 text-sm">
    <a href="/dashboard" class="text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
        </svg>
    </a>
    
    @if(isset($breadcrumbs) && is_array($breadcrumbs))
        @foreach($breadcrumbs as $breadcrumb)
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
            
            @if($loop->last)
                <span class="text-gray-900 font-medium">{{ $breadcrumb['title'] ?? $breadcrumb }}</span>
            @else
                <a href="{{ $breadcrumb['url'] ?? '#' }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                    {{ $breadcrumb['title'] ?? $breadcrumb }}
                </a>
            @endif
        @endforeach
    @else
        @php
            $currentPath = request()->path();
            $segments = explode('/', trim($currentPath, '/'));
            $breadcrumbTitles = [
                'dashboard' => '대시보드',
                'projects' => '프로젝트', 
                'tasks' => '작업',
                'team' => '팀',
                'admin' => '관리자',
                'users' => '사용자',
                'settings' => '설정',
                'logs' => '로그',
                'backup' => '백업'
            ];
        @endphp
        
        @if(count($segments) > 0 && $segments[0] !== '')
            @foreach($segments as $index => $segment)
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
                
                @if($loop->last)
                    <span class="text-gray-900 font-medium">
                        {{ $breadcrumbTitles[$segment] ?? ucfirst($segment) }}
                    </span>
                @else
                    @php
                        $url = '/' . implode('/', array_slice($segments, 0, $index + 1));
                    @endphp
                    <a href="{{ $url }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                        {{ $breadcrumbTitles[$segment] ?? ucfirst($segment) }}
                    </a>
                @endif
            @endforeach
        @else
            <span class="text-gray-900 font-medium">대시보드</span>
        @endif
    @endif
</nav>