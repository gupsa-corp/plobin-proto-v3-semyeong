# Plobin Proto V3 - Blade 템플릿 구조

## 개요

이 문서는 Plobin Proto V3 프로젝트의 Blade 템플릿 구조와 네이밍 컨벤션을 설명합니다.
서비스는 **랜딩페이지**, **본 서비스**, **관리자** 3개 영역으로 구분되며, 각각 고유한 CSS/JS를 가집니다.

## 디렉토리 구조

```
resources/views/
├── 000-common-landing/          # 랜딩페이지 공통 컴포넌트
│   ├── head.blade.php          # HTML head (랜딩용 CSS/JS)
│   ├── header.blade.php        # 랜딩 헤더
│   └── footer.blade.php        # 랜딩 푸터
├── 000-common-service/          # 본 서비스 공통 컴포넌트
│   ├── head.blade.php          # HTML head (서비스용 CSS/JS)
│   ├── header.blade.php        # 서비스 헤더 (로그인된 사용자용)
│   ├── sidebar.blade.php       # 서비스 사이드바
│   └── footer.blade.php        # 서비스 푸터
├── 000-common-admin/            # 관리자 페이지 공통 컴포넌트
│   ├── head.blade.php          # HTML head (관리자용 CSS/JS)
│   ├── header.blade.php        # 관리자 헤더
│   ├── sidebar.blade.php       # 관리자 사이드바
│   └── footer.blade.php        # 관리자 푸터
├── 011-landing/                 # 랜딩 페이지
│   ├── index.blade.php         # 메인 레이아웃
│   └── body.blade.php          # 본문 내용
├── 021-login/                   # 로그인 페이지 (랜딩)
│   ├── index.blade.php         # 메인 레이아웃
│   └── body.blade.php          # 로그인 폼
├── 022-signup/                  # 회원가입 페이지 (랜딩)
│   ├── index.blade.php         # 메인 레이아웃
│   └── body.blade.php          # 회원가입 폼
├── 1XX-service-*/               # 본 서비스 페이지들 (100번대)
│   ├── 101-dashboard/          # 대시보드
│   ├── 102-profile/            # 프로필
│   └── 103-settings/           # 설정
└── 9XX-admin-*/                 # 관리자 페이지들 (900번대)
    ├── 901-dashboard/          # 관리자 대시보드
    ├── 902-users/              # 사용자 관리
    └── 903-settings/           # 시스템 설정
```

## 네이밍 컨벤션

### 디렉토리 네이밍
- `000-common-{type}`: 공통 컴포넌트 (landing/service/admin)
- `0{n}{n}-{page-name}`: 랜딩 페이지 (001-099)
- `1{n}{n}-service-{page-name}`: 본 서비스 페이지 (100-199)  
- `9{n}{n}-admin-{page-name}`: 관리자 페이지 (900-999)

### 파일 네이밍
- `index.blade.php`: 페이지의 메인 레이아웃 파일
- `body.blade.php`: 페이지의 본문 콘텐츠
- `head.blade.php`: HTML head 섹션
- `header.blade.php`: 페이지 헤더
- `footer.blade.php`: 페이지 푸터

## 템플릿 구조 패턴

### 1. 랜딩 페이지 레이아웃 (0XX-{page}/index.blade.php)

```html
<!DOCTYPE html>
<html lang="ko">
@include('000-common-landing.head')
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include('000-common-landing.header')
        @include('{current-page}.body')
        @include('000-common-landing.footer')
    </div>
</body>
</html>
```

### 2. 본 서비스 레이아웃 (1XX-service-{page}/index.blade.php)

```html
<!DOCTYPE html>
<html lang="ko">
@include('000-common-service.head')
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        @include('000-common-service.sidebar')
        <div class="flex-1 flex flex-col">
            @include('000-common-service.header')
            @include('{current-page}.body')
            @include('000-common-service.footer')
        </div>
    </div>
</body>
</html>
```

### 3. 관리자 페이지 레이아웃 (9XX-admin-{page}/index.blade.php)

```html
<!DOCTYPE html>
<html lang="ko">
@include('000-common-admin.head')
<body class="bg-gray-900 text-white">
    <div class="flex min-h-screen">
        @include('000-common-admin.sidebar')
        <div class="flex-1 flex flex-col">
            @include('000-common-admin.header')
            @include('{current-page}.body')
            @include('000-common-admin.footer')
        </div>
    </div>
</body>
</html>
```

## CSS/JS 분리 구조

### 랜딩 페이지용 (000-common-landing/head.blade.php)
```html
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plobin</title>
    
    <!-- 랜딩용 CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
    
    <!-- 랜딩용 JS -->
    <script defer src="{{ asset('js/landing.js') }}"></script>
</head>
```

### 본 서비스용 (000-common-service/head.blade.php)
```html
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plobin - 서비스</title>
    
    <!-- 서비스용 CSS -->
    <link href="{{ asset('css/service.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    
    <!-- 서비스용 JS -->
    <script defer src="{{ asset('js/service.js') }}"></script>
    <script defer src="{{ asset('js/dashboard.js') }}"></script>
</head>
```

### 관리자용 (000-common-admin/head.blade.php)
```html
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plobin - 관리자</title>
    
    <!-- 관리자용 CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-dark.css') }}" rel="stylesheet">
    
    <!-- 관리자용 JS -->
    <script defer src="{{ asset('js/admin.js') }}"></script>
    <script defer src="{{ asset('js/charts.js') }}"></script>
</head>
```

## CSS/JS 파일 구조

```
public/
├── css/
│   ├── landing.css        # 랜딩페이지 전용
│   ├── service.css        # 본 서비스 공통
│   ├── dashboard.css      # 대시보드 전용
│   ├── admin.css          # 관리자 공통
│   └── admin-dark.css     # 관리자 다크테마
└── js/
    ├── landing.js         # 랜딩페이지 전용
    ├── service.js         # 본 서비스 공통
    ├── dashboard.js       # 대시보드 전용
    ├── admin.js           # 관리자 공통
    └── charts.js          # 관리자 차트 기능
```

## 스타일링 가이드

### 랜딩 페이지
- **배경**: `bg-gray-50` (밝은 회색)
- **레이아웃**: 세로 배치 (`flex flex-col`)
- **헤더**: 화이트 배경, 간단한 네비게이션

### 본 서비스
- **배경**: `bg-gray-100` (중간 회색)
- **레이아웃**: 사이드바 + 메인 (`flex`)
- **사이드바**: 네비게이션 메뉴
- **헤더**: 사용자 정보, 알림

### 관리자 페이지
- **배경**: `bg-gray-900 text-white` (다크 테마)
- **레이아웃**: 사이드바 + 메인 (`flex`)
- **사이드바**: 관리 메뉴
- **헤더**: 관리자 도구, 시스템 상태

## 라우팅 연결 예시

```php
// 랜딩 페이지 (0XX)
Route::get('/', function () { return view('011-landing.index'); });
Route::get('/login', function () { return view('021-login.index'); });
Route::get('/signup', function () { return view('022-signup.index'); });

// 본 서비스 (1XX)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () { return view('101-service-dashboard.index'); });
    Route::get('/profile', function () { return view('102-service-profile.index'); });
    Route::get('/settings', function () { return view('103-service-settings.index'); });
});

// 관리자 페이지 (9XX)
Route::middleware('admin')->group(function () {
    Route::get('/admin', function () { return view('901-admin-dashboard.index'); });
    Route::get('/admin/users', function () { return view('902-admin-users.index'); });
    Route::get('/admin/settings', function () { return view('903-admin-settings.index'); });
});
```

## 확장 가이드라인

### 새로운 랜딩 페이지 추가
1. **디렉토리**: `0{n}{n}-{page-name}` (001-099)
2. **공통 컴포넌트**: `000-common-landing` 사용
3. **CSS/JS**: `landing.css`, `landing.js`

### 새로운 서비스 페이지 추가  
1. **디렉토리**: `1{n}{n}-service-{page-name}` (100-199)
2. **공통 컴포넌트**: `000-common-service` 사용
3. **CSS/JS**: `service.css`, 필요시 페이지별 CSS

### 새로운 관리자 페이지 추가
1. **디렉토리**: `9{n}{n}-admin-{page-name}` (900-999)  
2. **공통 컴포넌트**: `000-common-admin` 사용
3. **CSS/JS**: `admin.css`, 필요시 기능별 JS

## 구현 순서 권장사항

1. **랜딩페이지** 먼저 완성 (001-099)
2. **본 서비스** 핵심 기능 (100-199)  
3. **관리자 페이지** 마지막 구현 (900-999)

## 주의사항

### CSS/JS 분리 관련
- 각 영역별 CSS/JS는 완전히 분리하여 관리
- 공통 라이브러리는 필요한 영역에만 로드
- 번들링 시 영역별로 분리하여 빌드

### 공통 컴포넌트 수정
- `000-common-{type}` 수정 시 해당 영역 전체에 영향
- 수정 전 반드시 영향 범위 확인
- 버전 관리를 통해 변경 이력 추적

### 네이밍 규칙 준수
- 번호 체계를 반드시 준수 (001-099, 100-199, 900-999)
- 파일명에 한글 사용 금지
- kebab-case 사용 (`service-dashboard`, `admin-users`)

### 성능 최적화
- 영역별 CSS/JS 분리로 불필요한 로딩 방지
- 지연 로딩(`defer`) 활용
- 필요시 페이지별 추가 CSS/JS 로드

### 보안 고려사항
- 관리자 페이지는 별도 미들웨어로 보호
- 서비스 페이지는 인증 미들웨어 필수
- 각 영역별 권한 체계 구분

### 반응형 디자인
- 모든 영역에서 반응형 디자인 필수
- 각 영역별 브레이크포인트 일관성 유지
- 한국어 사용자를 위해 `lang="ko"` 설정 유지