@extends('700-page-sandbox.000-index')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- 페이지 헤더 -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">자료 다운로드</h1>
        <p class="mt-2 text-gray-600">E2E 테스트용 자료와 샘플 파일을 다운로드할 수 있습니다.</p>
    </div>

    <!-- 다운로드 카테고리 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- 테스트 데이터 -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">테스트 데이터</h3>
            </div>
            <p class="text-gray-600 mb-4">E2E 테스트에 사용할 수 있는 샘플 데이터 파일들</p>
            <div class="space-y-2">
                <button onclick="downloadFile('test-users.csv')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    📄 사용자 목록 (CSV)
                </button>
                <button onclick="downloadFile('test-organizations.xlsx')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    📊 조직 데이터 (Excel)
                </button>
                <button onclick="downloadFile('test-projects.json')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    💾 프로젝트 데이터 (JSON)
                </button>
            </div>
        </div>

        <!-- 문서 템플릿 -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">문서 템플릿</h3>
            </div>
            <p class="text-gray-600 mb-4">프로젝트에서 사용할 수 있는 문서 템플릿</p>
            <div class="space-y-2">
                <button onclick="downloadFile('project-plan-template.docx')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    📝 프로젝트 계획 템플릿
                </button>
                <button onclick="downloadFile('meeting-minutes-template.docx')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    📋 회의록 템플릿
                </button>
                <button onclick="downloadFile('requirements-template.docx')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    📄 요구사항 명세서 템플릿
                </button>
            </div>
        </div>

        <!-- 이미지 자료 -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">이미지 자료</h3>
            </div>
            <p class="text-gray-600 mb-4">테스트 및 예시용 이미지 파일들</p>
            <div class="space-y-2">
                <button onclick="downloadFile('sample-avatar.png')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    🖼️ 샘플 아바타 이미지
                </button>
                <button onclick="downloadFile('company-logo.svg')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    🏢 회사 로고 (SVG)
                </button>
                <button onclick="downloadFile('placeholder-banner.jpg')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    🌅 플레이스홀더 배너
                </button>
            </div>
        </div>

        <!-- 개발 도구 -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">개발 도구</h3>
            </div>
            <p class="text-gray-600 mb-4">개발에 도움이 되는 스크립트와 설정 파일</p>
            <div class="space-y-2">
                <button onclick="downloadFile('e2e-test-scripts.zip')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    ⚙️ E2E 테스트 스크립트
                </button>
                <button onclick="downloadFile('database-seeds.sql')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    🗄️ 데이터베이스 시드 파일
                </button>
                <button onclick="downloadFile('api-postman-collection.json')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    📡 API 테스트 컬렉션
                </button>
            </div>
        </div>

        <!-- 백업 파일 -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">백업 파일</h3>
            </div>
            <p class="text-gray-600 mb-4">시스템 백업 및 복원 파일</p>
            <div class="space-y-2">
                <button onclick="downloadFile('database-backup.sql')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    💾 데이터베이스 백업
                </button>
                <button onclick="downloadFile('config-backup.json')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    ⚙️ 설정 파일 백업
                </button>
                <button onclick="downloadFile('storage-backup.tar.gz')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    📦 스토리지 백업
                </button>
            </div>
        </div>

        <!-- 사용자 매뉴얼 -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 ml-3">사용자 매뉴얼</h3>
            </div>
            <p class="text-gray-600 mb-4">시스템 사용법 및 가이드 문서</p>
            <div class="space-y-2">
                <button onclick="downloadFile('user-manual.pdf')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    📖 사용자 매뉴얼
                </button>
                <button onclick="downloadFile('admin-guide.pdf')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    👑 관리자 가이드
                </button>
                <button onclick="downloadFile('troubleshooting-guide.pdf')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-md text-sm text-gray-700 transition-colors">
                    🔧 문제해결 가이드
                </button>
            </div>
        </div>
    </div>

    <!-- 다운로드 통계 -->
    <div class="mt-12 bg-gray-50 rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">다운로드 통계</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div id="total-downloads" class="text-2xl font-bold text-blue-600">1,234</div>
                <div class="text-sm text-gray-600">총 다운로드</div>
            </div>
            <div class="text-center">
                <div id="monthly-downloads" class="text-2xl font-bold text-green-600">567</div>
                <div class="text-sm text-gray-600">이번 달</div>
            </div>
            <div class="text-center">
                <div id="weekly-downloads" class="text-2xl font-bold text-purple-600">89</div>
                <div class="text-sm text-gray-600">이번 주</div>
            </div>
            <div class="text-center">
                <div id="daily-downloads" class="text-2xl font-bold text-orange-600">12</div>
                <div class="text-sm text-gray-600">오늘</div>
            </div>
        </div>
    </div>
</div>

<!-- 다운로드 진행 상황 표시 모달 -->
<div id="downloadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-blue-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4 4m4-4v12"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">파일 다운로드 중</h3>
            <p class="text-gray-600 mb-4">파일을 준비하고 있습니다. 잠시만 기다려주세요.</p>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="downloadProgress" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <button onclick="closeDownloadModal()" class="mt-4 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                취소
            </button>
        </div>
    </div>
</div>

<script>
function downloadFile(filename) {
    // 모달 표시
    const modal = document.getElementById('downloadModal');
    const progress = document.getElementById('downloadProgress');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // 진행률 애니메이션
    let currentProgress = 0;
    const progressInterval = setInterval(() => {
        currentProgress += Math.random() * 20;
        if (currentProgress > 90) {
            currentProgress = 90;
        }
        progress.style.width = currentProgress + '%';
    }, 200);
    
    // 실제 파일 다운로드
    setTimeout(() => {
        clearInterval(progressInterval);
        progress.style.width = '100%';
        
        // 파일 다운로드 실행
        const downloadUrl = `/sandbox/downloads/file/${filename}`;
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // 1초 후 모달 닫기
        setTimeout(() => {
            closeDownloadModal();
        }, 1000);
    }, 2000);
}

function closeDownloadModal() {
    const modal = document.getElementById('downloadModal');
    const progress = document.getElementById('downloadProgress');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    progress.style.width = '0%';
}

// 페이지 로드 시 통계 데이터 로드
document.addEventListener('DOMContentLoaded', function() {
    loadDownloadStats();
});

function loadDownloadStats() {
    fetch('/sandbox/downloads/stats')
        .then(response => response.json())
        .then(data => {
            // 통계 데이터 업데이트 (실제 API에서 받은 데이터 사용)
            updateStatElement('total-downloads', data.total_downloads);
            updateStatElement('monthly-downloads', data.monthly_downloads);
            updateStatElement('weekly-downloads', data.weekly_downloads);
            updateStatElement('daily-downloads', data.daily_downloads);
        })
        .catch(error => {
            console.error('통계 데이터 로드 실패:', error);
        });
}

function updateStatElement(id, value) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = value.toLocaleString();
    }
}
</script>
@endsection