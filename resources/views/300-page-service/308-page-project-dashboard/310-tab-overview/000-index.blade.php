{{-- 프로젝트 개요 탭 --}}
<div style="padding: 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        {{-- 탭 헤더 --}}
        <div style="border-bottom: 1px solid #E5E7EB; margin-bottom: 20px;" x-data="{ activeTab: 'overview' }">
            <nav style="display: flex; gap: 32px;">
                <button @click="activeTab = 'overview'" 
                        :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'"
                        style="padding: 12px 0; border-bottom: 2px solid; font-weight: 500; background: none; border-top: none; border-left: none; border-right: none;">
                    개요
                </button>
                <button @click="activeTab = 'tasks'" 
                        :class="activeTab === 'tasks' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'"
                        style="padding: 12px 0; border-bottom: 2px solid; font-weight: 500; background: none; border-top: none; border-left: none; border-right: none;">
                    작업
                </button>
                <button @click="activeTab = 'files'" 
                        :class="activeTab === 'files' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'"
                        style="padding: 12px 0; border-bottom: 2px solid; font-weight: 500; background: none; border-top: none; border-left: none; border-right: none;">
                    파일
                </button>
            </nav>
            
            {{-- 탭 콘텐츠 --}}
            <div style="margin-top: 20px;">
                {{-- 개요 탭 --}}
                <div x-show="activeTab === 'overview'" style="display: none;" x-transition>
                    @include('300-page-service.308-page-project-dashboard.310-tab-overview.100-overview-content')
                </div>
                
                {{-- 작업 탭 --}}
                <div x-show="activeTab === 'tasks'" style="display: none;" x-transition>
                    @include('300-page-service.308-page-project-dashboard.320-tab-tasks.100-tasks-content')
                </div>
                
                {{-- 파일 탭 --}}
                <div x-show="activeTab === 'files'" style="display: none;" x-transition>
                    @include('300-page-service.308-page-project-dashboard.330-tab-files.100-files-content')
                </div>
            </div>
        </div>
    </div>
</div>