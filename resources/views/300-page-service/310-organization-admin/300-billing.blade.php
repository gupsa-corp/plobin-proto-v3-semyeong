<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '결제 관리'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('300-page-service.310-organization-admin.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('300-page-service.310-organization-admin.100-header-main')
            
            {{-- 결제 관리 메인 콘텐츠 --}}
            <div class="p-6">
                {{-- 페이지 헤더 --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">결제 관리</h2>
                            <p class="text-gray-600 mt-1">조직의 요금제 및 결제 정보를 관리합니다</p>
                        </div>
                        <div class="flex gap-3">
                            <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                영수증 다운로드
                            </button>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                                요금제 변경
                            </button>
                        </div>
                    </div>
                </div>

                {{-- 현재 플랜 정보 --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-semibold">Pro 플랜</h3>
                                <span class="px-2.5 py-0.5 bg-blue-500 rounded-full text-xs font-medium">활성</span>
                            </div>
                            <p class="text-blue-100 mb-4">팀 협업을 위한 전문가 플랜</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <p class="text-blue-100 text-sm">월 요금</p>
                                    <p class="text-2xl font-bold">₩99,000</p>
                                </div>
                                <div>
                                    <p class="text-blue-100 text-sm">다음 결제일</p>
                                    <p class="text-lg font-medium">2024년 4월 15일</p>
                                </div>
                                <div>
                                    <p class="text-blue-100 text-sm">사용 중인 시트</p>
                                    <p class="text-lg font-medium">24 / 50</p>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-24 h-24 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 사용량 및 한도 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">월간 사용량</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">활성 멤버</span>
                                    <span class="text-sm font-medium">24 / 50</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 48%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">프로젝트</span>
                                    <span class="text-sm font-medium">12 / 무제한</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 20%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">스토리지</span>
                                    <span class="text-sm font-medium">145GB / 500GB</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 29%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">이번 달 요약</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">활성 사용자</p>
                                        <p class="font-medium">24명</p>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">₩99,000</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">추가 스토리지</p>
                                        <p class="font-medium">0GB</p>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">₩0</span>
                            </div>
                            
                            <div class="border-t pt-3">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-900">총 금액</span>
                                    <span class="font-bold text-gray-900">₩99,000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 결제 수단 --}}
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">결제 수단</h3>
                        <button class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            + 결제 수단 추가
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-8 bg-blue-600 rounded flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">VISA</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">**** **** **** 1234</p>
                                    <p class="text-sm text-gray-500">만료일: 12/26</p>
                                </div>
                                <span class="px-2.5 py-0.5 bg-green-100 text-green-800 text-xs font-medium rounded-full">기본</span>
                            </div>
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:text-blue-800 text-sm">편집</button>
                                <button class="text-red-600 hover:text-red-800 text-sm">삭제</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 결제 내역 --}}
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">결제 내역</h3>
                            <div class="flex gap-2">
                                <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option>최근 6개월</option>
                                    <option>최근 1년</option>
                                    <option>전체</option>
                                </select>
                                <button class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
                                    내보내기
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">날짜</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">설명</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">금액</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">상태</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">영수증</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024.03.15</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Pro 플랜 월간 구독</div>
                                            <div class="text-sm text-gray-500">2024년 3월 15일 - 4월 15일</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₩99,000</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">결제 완료</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900">다운로드</button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024.02.15</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Pro 플랜 월간 구독</div>
                                            <div class="text-sm text-gray-500">2024년 2월 15일 - 3월 15일</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₩99,000</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">결제 완료</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900">다운로드</button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024.01.15</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Pro 플랜 월간 구독</div>
                                            <div class="text-sm text-gray-500">2024년 1월 15일 - 2월 15일</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₩99,000</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">결제 완료</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900">다운로드</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- 페이지네이션 --}}
                    <div class="px-6 py-3 flex items-center justify-between border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            총 25건 중 1-10건 표시
                        </div>
                        <div class="flex gap-1">
                            <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-500">이전</button>
                            <button class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm">1</button>
                            <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700">2</button>
                            <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700">3</button>
                            <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700">다음</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>