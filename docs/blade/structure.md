# Plobin Proto V3 - Blade 템플릿 구조

## 개요

이 문서는 Plobin Proto V3 프로젝트의 실제 Blade 템플릿 구조와 네이밍 컨벤션을 설명합니다.
서비스는 **랜딩페이지(100)**, **인증(200)**, **서비스(300)** 3개 영역으로 구분됩니다.

## 실제 디렉토리 구조

```
resources/views/
├── 100-page-landing/           # 랜딩페이지 영역
│   ├── 100-common/            # 랜딩페이지 공통 컴포넌트
│   │   ├── 100-header-main.blade.php      # 랜딩 헤더
│   │   ├── 301-layout-head.blade.php      # HTML head
│   │   ├── 302-layout-css-imports.blade.php  # CSS imports
│   │   └── 900-layout-footer.blade.php    # 랜딩 푸터
│   └── 101-page-landing-home/  # 메인 랜딩페이지
│       ├── 000-index.blade.php # 메인 레이아웃
│       └── 200-content-main.blade.php # 본문 내용
├── 200-page-auth/             # 인증 페이지 영역
│   ├── 200-common/            # 인증 공통 컴포넌트
│   │   ├── 100-header-main.blade.php      # 인증 헤더
│   │   ├── 301-layout-head.blade.php      # HTML head
│   │   ├── 302-layout-css-imports.blade.php  # CSS imports
│   │   └── 900-layout-footer.blade.php    # 인증 푸터
│   ├── 201-page-auth-login/   # 로그인 페이지
│   │   ├── 000-index.blade.php # 메인 레이아웃
│   │   ├── 200-content-main.blade.php # 로그인 폼
│   │   └── 500-ajax-login.blade.php # AJAX 로그인
│   ├── 202-page-auth-signup/  # 회원가입 페이지
│   │   ├── 000-index.blade.php # 메인 레이아웃
│   │   ├── 200-content-main.blade.php # 회원가입 폼
│   │   ├── 400-js-signup.blade.php # JavaScript
│   │   └── 500-ajax-signup.blade.php # AJAX 회원가입
│   ├── 203-page-auth-forgot-password/ # 비밀번호 찾기
│   │   ├── 000-index.blade.php
│   │   ├── 200-content-main.blade.php
│   │   └── 500-ajax-forgot-password.blade.php
│   └── 204-page-auth-reset-password/ # 비밀번호 재설정
│       ├── 000-index.blade.php
│       ├── 200-content-main.blade.php
│       └── 500-ajax-reset-password.blade.php
└── 300-page-service/          # 본 서비스 영역
    ├── 300-common/            # 서비스 공통 컴포넌트
    │   ├── 000-auth-token-manager.blade.php # 토큰 관리
    │   ├── 100-header-main.blade.php      # 메인 헤더
    │   ├── 102-header-breadcrumb.blade.php # 브레드크럼
    │   ├── 103-header-user-dropdown.blade.php # 사용자 드롭다운
    │   ├── 104-header-alarm.blade.php     # 알림 버튼
    │   ├── 105-header-settings.blade.php  # 설정 버튼
    │   ├── 106-header-mobile-menu.blade.php # 모바일 메뉴
    │   ├── 107-header-right-menu.blade.php # 우측 메뉴 통합
    │   ├── 200-sidebar-main.blade.php     # 메인 사이드바
    │   ├── 201-sidebar-navigation.blade.php # 네비게이션
    │   ├── 202-sidebar-organization-info.blade.php # 조직 정보
    │   ├── 301-layout-head.blade.php      # HTML head
    │   ├── 302-layout-css-imports.blade.php # CSS imports
    │   ├── 303-layout-js-imports.blade.php # JS imports
    │   ├── 500-ajax-get.blade.php         # AJAX GET
    │   ├── 500-ajax-post.blade.php        # AJAX POST
    │   ├── 500-ajax-put.blade.php         # AJAX PUT
    │   ├── 500-ajax-delete.blade.php      # AJAX DELETE
    │   └── 900-alpine-init.blade.php      # Alpine.js 초기화
    ├── 301-page-dashboard/    # 대시보드
    │   ├── 000-index.blade.php # 메인 레이아웃
    │   ├── 101-layout-body.blade.php # 레이아웃 본문
    │   ├── 200-content-auth-check.blade.php # 인증 체크
    │   ├── 201-content-organization-selection.blade.php # 조직 선택
    │   ├── 300-modal-create-organization.blade.php # 조직 생성 모달
    │   ├── 301-modal-create-success.blade.php # 성공 모달
    │   ├── 302-modal-organization-manager.blade.php # 조직 관리 모달
    │   ├── 400-js-dashboard.blade.php     # 대시보드 JS
    │   ├── 401-js-organization-selection.blade.php # 조직 선택 JS
    │   ├── 402-js-modal-*.blade.php       # 모달 관련 JS들
    │   ├── 500-ajax-organization-create.blade.php # 조직 생성 AJAX
    │   └── 600-data-sidebar.blade.php     # 사이드바 데이터
    └── 302-page-organization-dashboard/ # 조직 대시보드
        ├── 000-index.blade.php # 메인 레이아웃
        ├── 200-content-main.blade.php # 메인 컨텐츠
        ├── 300-modal-organization-manager.blade.php # 조직 관리 모달
        ├── 400-js-org-*.blade.php        # JS 함수들
        ├── 500-ajax-organization-*.blade.php # AJAX 함수들
        └── 600-data-sidebar.blade.php    # 사이드바 데이터
```

## 네이밍 컨벤션

### 디렉토리 네이밍
- `100-page-landing`: 랜딩페이지 영역 (100-199)
  - `100-common`: 랜딩페이지 공통 컴포넌트
  - `101-page-landing-home`: 메인 랜딩페이지
- `200-page-auth`: 인증 페이지 영역 (200-299)  
  - `200-common`: 인증 공통 컴포넌트
  - `201-page-auth-login`: 로그인 페이지
  - `202-page-auth-signup`: 회원가입 페이지
  - `203-page-auth-forgot-password`: 비밀번호 찾기
  - `204-page-auth-reset-password`: 비밀번호 재설정
- `300-page-service`: 서비스 영역 (300-399)
  - `300-common`: 서비스 공통 컴포넌트
  - `301-page-dashboard`: 대시보드
  - `302-page-organization-dashboard`: 조직 대시보드

### 파일 네이밍 (숫자 prefix 사용)
- `000-xxx.blade.php`: 인덱스 파일 (메인 레이아웃)
- `100-xxx.blade.php`: 헤더 관련 파일들
- `200-xxx.blade.php`: 메인 콘텐츠, 사이드바 파일들
- `300-xxx.blade.php`: 레이아웃, 모달 파일들
- `400-xxx.blade.php`: JavaScript 파일들
- `500-xxx.blade.php`: AJAX 요청 파일들
- `600-xxx.blade.php`: 데이터 관련 파일들
- `900-xxx.blade.php`: 초기화, 푸터 파일들

## CSS/JS 파일 연결 구조

### Vite 설정에 따른 자산 구조
```
resources/
├── css/
│   ├── 000-app-common.css      # 기본 공통 스타일
│   ├── 100-landing-common.css  # 랜딩페이지용 (100-199)
│   ├── 200-auth-common.css     # 인증페이지용 (200-299)
│   ├── 300-service-common.css  # 서비스용 (300-399)
│   └── 900-admin-common.css    # 관리자용 (900-999)
└── js/
    ├── 000-app-common.js       # 기본 공통 기능
    ├── 000-bootstrap-common.js # Axios 등 기본 라이브러리
    ├── 100-landing-common.js   # 랜딩페이지용
    ├── 200-auth-common.js      # 인증페이지용
    ├── 300-service-common.js   # 서비스용
    └── 900-admin-common.js     # 관리자용
```

### Head 컴포넌트별 자산 로드

#### 랜딩페이지용 (100-page-landing/100-common/301-layout-head.blade.php)
```html
@vite(['resources/css/100-landing-common.css', 'resources/js/100-landing-common.js'])
```

#### 인증페이지용 (200-page-auth/200-common/301-layout-head.blade.php)
```html
@vite(['resources/css/200-auth-common.css', 'resources/js/200-auth-common.js'])
```

#### 서비스용 (300-page-service/300-common/301-layout-head.blade.php)
```html
@vite(['resources/css/300-service-common.css', 'resources/js/300-service-common.js'])
```

## 템플릿 구조 패턴

### 1. 랜딩 페이지 레이아웃 (101-page-landing-home/000-index.blade.php)

```html
<!DOCTYPE html>
<html lang="ko">
@include('100-page-landing.100-common.301-layout-head')
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include('100-page-landing.100-common.100-header-main')
        @include('100-page-landing.101-page-landing-home.200-content-main')
        @include('100-page-landing.100-common.900-layout-footer')
    </div>
</body>
</html>
```

### 2. 인증 페이지 레이아웃 (2XX-page-auth-{page}/000-index.blade.php)

```html
<!DOCTYPE html>
<html lang="ko">
@include('200-page-auth.200-common.301-layout-head')
<body class="bg-white min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include('200-page-auth.200-common.100-header-main')
        @include('200-page-auth.{current-page}.200-content-main')
        @include('200-page-auth.200-common.900-layout-footer')
    </div>
    
    <!-- AJAX 스크립트 포함 -->
    @include('200-page-auth.{current-page}.500-ajax-{action}')
    
    <!-- JavaScript 포함 (있는 경우) -->
    @isset('jsFile')
        @include('200-page-auth.{current-page}.400-js-{action}')
    @endisset
</body>
</html>
```

### 3. 서비스 페이지 레이아웃 (3XX-page-{service}/000-index.blade.php)

```html
<!DOCTYPE html>
<html lang="ko">
@include('300-page-service.300-common.301-layout-head')
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        @include('300-page-service.300-common.200-sidebar-main')
        <div class="flex-1 flex flex-col">
            @include('300-page-service.300-common.100-header-main')
            @include('300-page-service.{current-page}.200-content-main')
        </div>
    </div>
    
    <!-- 공통 AJAX 함수들 -->
    @include('300-page-service.300-common.500-ajax-get')
    @include('300-page-service.300-common.500-ajax-post')
    @include('300-page-service.300-common.500-ajax-put')
    @include('300-page-service.300-common.500-ajax-delete')
    
    <!-- 페이지별 스크립트들 -->
    @include('300-page-service.{current-page}.400-js-{function}')
    @include('300-page-service.{current-page}.500-ajax-{specific-action}')
    
    <!-- Alpine.js 초기화 -->
    @include('300-page-service.300-common.900-alpine-init')
</body>
</html>
```

## 라우팅 연결 예시

```php
// 랜딩 페이지 (100번대)
Route::get('/', function () { 
    return view('100-page-landing.101-page-landing-home.000-index'); 
});

// 인증 페이지 (200번대) 
Route::get('/login', function () { 
    return view('200-page-auth.201-page-auth-login.000-index'); 
});
Route::get('/signup', function () { 
    return view('200-page-auth.202-page-auth-signup.000-index'); 
});
Route::get('/forgot-password', function () { 
    return view('200-page-auth.203-page-auth-forgot-password.000-index'); 
});
Route::get('/reset-password', function () { 
    return view('200-page-auth.204-page-auth-reset-password.000-index'); 
});

// 본 서비스 (300번대)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () { 
        return view('300-page-service.301-page-dashboard.000-index'); 
    });
    Route::get('/organization/{id}', function () { 
        return view('300-page-service.302-page-organization-dashboard.000-index'); 
    });
});
```

## 스타일링 가이드

### 랜딩 페이지 (100번대)
- **배경**: `bg-gray-50` (밝은 회색)
- **레이아웃**: 세로 배치 (`flex flex-col`)
- **헤더**: 화이트 배경, 간단한 네비게이션
- **푸터**: 기본적인 브랜딩 정보

### 인증 페이지 (200번대)
- **배경**: `bg-white` (화이트)
- **레이아웃**: 세로 배치, 중앙 정렬
- **헤더**: 최소한의 브랜딩
- **폼**: 중앙 배치, 그림자 효과
- **AJAX**: 비동기 폼 처리

### 서비스 페이지 (300번대)
- **배경**: `bg-gray-100` (중간 회색)
- **레이아웃**: 사이드바 + 메인 (`flex`)
- **사이드바**: 네비게이션 메뉴, 조직 정보
- **헤더**: 사용자 정보, 알림, 설정
- **모달**: 조직 생성, 관리 등
- **Alpine.js**: 반응형 UI 컴포넌트

## 확장 가이드라인

### 새로운 페이지 추가

1. **디렉토리 생성**: 번호 체계에 따라 적절한 번호 선택
   - 랜딩페이지: 100-199
   - 인증페이지: 200-299  
   - 서비스페이지: 300-399

2. **필수 파일 생성**:
   - `000-index.blade.php`: 메인 레이아웃
   - `200-content-main.blade.php`: 본문 내용

3. **공통 컴포넌트 활용**: 해당 영역의 `{n}00-common` 폴더 사용

4. **라우트 등록**: `routes/web.php`에 적절한 라우트 추가

5. **숫자 prefix 사용**: 모든 파일은 적절한 숫자 prefix 사용
   - 000: 인덱스
   - 100: 헤더  
   - 200: 콘텐츠/사이드바
   - 300: 레이아웃/모달
   - 400: JavaScript
   - 500: AJAX
   - 600: 데이터
   - 900: 푸터/초기화

### CSS/JS 개발

1. **개발 모드**: `npm run dev` 실행으로 실시간 빌드
2. **프로덕션 빌드**: `npm run build`로 최적화된 빌드
3. **파일 수정**: `resources/css/`, `resources/js/` 디렉토리에서 작업
4. **자동 반영**: Vite가 변경사항을 자동으로 브라우저에 반영

## 주의사항

### 번호 체계 준수
- 각 영역별 번호 범위를 반드시 준수 (100-199, 200-299, 300-399)
- 신규 페이지는 각 영역에서 사용 가능한 다음 번호 사용
- **숫자 prefix 필수**: 모든 파일에 적절한 숫자 prefix 사용

### 공통 컴포넌트 수정
- `{n}00-common` 폴더 수정 시 해당 영역 전체에 영향
- 수정 전 반드시 영향 범위 확인 필요

### 파일 네이밍 일관성
- **절대 금지**: 숫자 prefix 없는 파일 생성
- **권장**: 기능별 숫자 구간 사용 (000, 100, 200, 300, 400, 500, 600, 900)
- **필수**: 의미 있는 파일명 사용

### CSS/JS 분리
- 영역별 CSS/JS는 완전히 분리하여 관리
- 불필요한 자산 로딩 방지로 성능 최적화

### Vite 개발 서버 활용
- 개발 시 반드시 `npm run dev` 실행
- 실시간 빌드로 개발 효율성 극대화
- 프로덕션 배포 시에만 `npm run build` 사용