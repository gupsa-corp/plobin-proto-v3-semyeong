# Plobin Proto V3 - Blade 템플릿 구조

## 개요

이 문서는 Plobin Proto V3 프로젝트의 실제 Blade 템플릿 구조와 네이밍 컨벤션을 설명합니다.
서비스는 **랜딩페이지(100)**, **인증(200)**, **서비스(300)**, **관리자(900)** 4개 영역으로 구분됩니다.

## 실제 디렉토리 구조

```
resources/views/
├── 100-landing-common/         # 랜딩페이지 공통 컴포넌트
│   ├── head.blade.php          # HTML head (랜딩용 CSS/JS)
│   ├── header.blade.php        # 랜딩 헤더
│   └── footer.blade.php        # 랜딩 푸터
├── 101-landing-home/           # 메인 랜딩페이지
│   ├── index.blade.php         # 메인 레이아웃
│   └── body.blade.php          # 본문 내용
├── 200-auth-common/            # 인증 페이지 공통 컴포넌트
│   ├── head.blade.php          # HTML head (인증용 CSS/JS)
│   ├── header.blade.php        # 인증 헤더
│   └── footer.blade.php        # 인증 푸터
├── 201-auth-login/             # 로그인 페이지
│   ├── index.blade.php         # 메인 레이아웃
│   └── body.blade.php          # 로그인 폼
├── 202-auth-signup/            # 회원가입 페이지
│   ├── index.blade.php         # 메인 레이아웃
│   └── body.blade.php          # 회원가입 폼
├── 300-service-common/         # 본 서비스 공통 컴포넌트
│   ├── head.blade.php          # HTML head (서비스용 CSS/JS)
│   ├── header.blade.php        # 서비스 헤더 (로그인된 사용자용)
│   ├── sidebar.blade.php       # 서비스 사이드바
│   └── footer.blade.php        # 서비스 푸터
├── 301-service-dashboard/      # 서비스 대시보드
│   ├── index.blade.php         # 메인 레이아웃
│   └── body.blade.php          # 대시보드 내용
├── 900-admin-common/           # 관리자 페이지 공통 컴포넌트
│   ├── head.blade.php          # HTML head (관리자용 CSS/JS)
│   ├── header.blade.php        # 관리자 헤더
│   ├── sidebar.blade.php       # 관리자 사이드바
│   └── footer.blade.php        # 관리자 푸터
└── 901-admin-dashboard/        # 관리자 대시보드
    ├── index.blade.php         # 메인 레이아웃
    └── body.blade.php          # 관리자 대시보드 내용
```

## 네이밍 컨벤션

### 디렉토리 네이밍
- `1{n}{n}-{area}-common`: 랜딩페이지 공통 컴포넌트 (100-199)
- `1{n}{n}-{area}-{page}`: 랜딩페이지 (100-199)
- `2{n}{n}-auth-common`: 인증 페이지 공통 컴포넌트 (200-299)
- `2{n}{n}-auth-{page}`: 인증 페이지 (200-299)
- `3{n}{n}-service-common`: 서비스 공통 컴포넌트 (300-399)
- `3{n}{n}-service-{page}`: 서비스 페이지 (300-399)
- `9{n}{n}-admin-common`: 관리자 공통 컴포넌트 (900-999)
- `9{n}{n}-admin-{page}`: 관리자 페이지 (900-999)

### 파일 네이밍
- `index.blade.php`: 페이지의 메인 레이아웃 파일
- `body.blade.php`: 페이지의 본문 콘텐츠
- `head.blade.php`: HTML head 섹션 (공통 컴포넌트만)
- `header.blade.php`: 페이지 헤더 (공통 컴포넌트만)
- `footer.blade.php`: 페이지 푸터 (공통 컴포넌트만)

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

#### 랜딩페이지용 (100-landing-common/head.blade.php)
```html
@vite(['resources/css/100-landing-common.css', 'resources/js/100-landing-common.js'])
```

#### 인증페이지용 (200-auth-common/head.blade.php)
```html
@vite(['resources/css/200-auth-common.css', 'resources/js/200-auth-common.js'])
```

#### 서비스용 (300-service-common/head.blade.php)
```html
@vite(['resources/css/300-service-common.css', 'resources/js/300-service-common.js'])
```

#### 관리자용 (900-admin-common/head.blade.php)
```html
@vite(['resources/css/900-admin-common.css', 'resources/js/900-admin-common.js'])
```

## 템플릿 구조 패턴

### 1. 랜딩 페이지 레이아웃 (1XX-landing-{page}/index.blade.php)

```html
<!DOCTYPE html>
<html lang="ko">
@include('100-landing-common.head')
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include('100-landing-common.header')
        @include('{current-page}.body')
        @include('100-landing-common.footer')
    </div>
</body>
</html>
```

### 2. 인증 페이지 레이아웃 (2XX-auth-{page}/index.blade.php)

```html
<!DOCTYPE html>
<html lang="ko">
@include('200-auth-common.head')
<body class="bg-white min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include('200-auth-common.header')
        @include('{current-page}.body')
        @include('200-auth-common.footer')
    </div>
</body>
</html>
```

### 3. 서비스 페이지 레이아웃 (3XX-service-{page}/index.blade.php)

```html
<!DOCTYPE html>
<html lang="ko">
@include('300-service-common.head')
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        @include('300-service-common.sidebar')
        <div class="flex-1 flex flex-col">
            @include('300-service-common.header')
            @include('{current-page}.body')
            @include('300-service-common.footer')
        </div>
    </div>
</body>
</html>
```

### 4. 관리자 페이지 레이아웃 (9XX-admin-{page}/index.blade.php)

```html
<!DOCTYPE html>
<html lang="ko">
@include('900-admin-common.head')
<body class="bg-gray-900 text-white">
    <div class="flex min-h-screen">
        @include('900-admin-common.sidebar')
        <div class="flex-1 flex flex-col">
            @include('900-admin-common.header')
            @include('{current-page}.body')
            @include('900-admin-common.footer')
        </div>
    </div>
</body>
</html>
```

## 라우팅 연결 예시

```php
// 랜딩 페이지 (100번대)
Route::get('/', function () { return view('101-landing-home.index'); });

// 인증 페이지 (200번대) 
Route::get('/login', function () { return view('201-auth-login.index'); });
Route::get('/signup', function () { return view('202-auth-signup.index'); });

// 본 서비스 (300번대)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () { return view('301-service-dashboard.index'); });
});

// 관리자 페이지 (900번대)
Route::middleware('admin')->group(function () {
    Route::get('/admin', function () { return view('901-admin-dashboard.index'); });
});
```

## 스타일링 가이드

### 랜딩 페이지 (100번대)
- **배경**: `bg-gray-50` (밝은 회색)
- **레이아웃**: 세로 배치 (`flex flex-col`)
- **헤더**: 화이트 배경, 간단한 네비게이션

### 인증 페이지 (200번대)
- **배경**: `bg-white` (화이트)
- **레이아웃**: 세로 배치, 중앙 정렬
- **헤더**: 최소한의 브랜딩

### 서비스 페이지 (300번대)
- **배경**: `bg-gray-100` (중간 회색)
- **레이아웃**: 사이드바 + 메인 (`flex`)
- **사이드바**: 네비게이션 메뉴
- **헤더**: 사용자 정보, 알림

### 관리자 페이지 (900번대)
- **배경**: `bg-gray-900 text-white` (다크 테마)
- **레이아웃**: 사이드바 + 메인 (`flex`)
- **사이드바**: 관리 메뉴
- **헤더**: 관리자 도구, 시스템 상태

## 확장 가이드라인

### 새로운 페이지 추가

1. **디렉토리 생성**: 번호 체계에 따라 적절한 번호 선택
   - 랜딩페이지: 100-199
   - 인증페이지: 200-299  
   - 서비스페이지: 300-399
   - 관리자페이지: 900-999

2. **필수 파일 생성**:
   - `index.blade.php`: 메인 레이아웃
   - `body.blade.php`: 본문 내용

3. **공통 컴포넌트 활용**: 해당 영역의 `{n}XX-{area}-common` 사용

4. **라우트 등록**: `routes/web.php`에 적절한 라우트 추가

### CSS/JS 개발

1. **개발 모드**: `npm run dev` 실행으로 실시간 빌드
2. **프로덕션 빌드**: `npm run build`로 최적화된 빌드
3. **파일 수정**: `resources/css/`, `resources/js/` 디렉토리에서 작업
4. **자동 반영**: Vite가 변경사항을 자동으로 브라우저에 반영

## 주의사항

### 번호 체계 준수
- 각 영역별 번호 범위를 반드시 준수
- 신규 페이지는 각 영역에서 사용 가능한 다음 번호 사용

### 공통 컴포넌트 수정
- `{n}XX-{area}-common` 수정 시 해당 영역 전체에 영향
- 수정 전 반드시 영향 범위 확인 필요

### CSS/JS 분리
- 영역별 CSS/JS는 완전히 분리하여 관리
- 불필요한 자산 로딩 방지로 성능 최적화

### Vite 개발 서버 활용
- 개발 시 반드시 `npm run dev` 실행
- 실시간 빌드로 개발 효율성 극대화
- 프로덕션 배포 시에만 `npm run build` 사용