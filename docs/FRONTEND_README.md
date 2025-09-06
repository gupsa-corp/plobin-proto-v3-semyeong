# Plobin Proto V3 - Blade 템플릿 구조

1. Auth 처리는 300-common/000-auth-token-manager.blade.php 에서만 관리할 것
2. /300-page-service 폴더가 최상단 html 구조. 벗어나지 말것

## 실제 디렉토리 구조 (수정 X)

```
resources/views/
├── 100-page-landing/           # 랜딩페이지 영역
│   ├── 100-common/            # 랜딩페이지 공통 컴포넌트
│   │   ├── 100-header-main.blade.php      # 랜딩 헤더
│   │   ├── 301-layout-head.blade.php      # HTML head
│   │   ├── 302-layout-css-imports.blade.php  # CSS imports
│   │   └── 900-layout-footer.blade.php    # 랜딩 푸터
├── 200-page-auth/             # 인증 페이지 영역
│   ├── 200-common/            # 인증 공통 컴포넌트
│   │   ├── 100-header-main.blade.php      # 인증 헤더
│   │   ├── 301-layout-head.blade.php      # HTML head
│   │   ├── 302-layout-css-imports.blade.php  # CSS imports
│   │   └── 900-layout-footer.blade.php    # 인증 푸터
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
- `400-xxx.blade.php`: JavaScript 파일들 (**필수: 400번대 사용**)
- `500-xxx.blade.php`: AJAX 요청 파일들
- `600-xxx.blade.php`: 데이터 관련 파일들
- `900-xxx.blade.php`: 초기화, 푸터 파일들

#### JavaScript 파일 세분화 규칙 (400번대 사용 필수)
JavaScript 파일이 복잡할 경우, 다음과 같이 기능별로 분리하여 관리:

**예시: 회원가입 페이지 JavaScript 분리**
- `400-js-signup.blade.php`: 메인 JavaScript 파일 (통합 include + 통합함수 + 초기화)
- `400-js-global-state.blade.php`: 전역 상태 변수들
- `401-js-country-loader.blade.php`: 국가 목록 로드 기능
- `402-js-name-validation.blade.php`: 이름 필드 검증 기능  

**JavaScript 분리 시 주의사항:**
- 모든 JavaScript 관련 파일은 **반드시 400번대 사용**
- 메인 파일(400-js-{page}.blade.php)에서 @include로 분리된 파일들 통합
- 순서대로 번호 부여 (400, 401, 402, ... 408)
- 각 파일은 특정 기능만 담당하도록 분리
- 파일명에 기능을 명확히 표시 (validation, handler, loader 등)
- **간단한 통합 함수와 초기화 코드는 메인 파일에 직접 작성**


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
   - 400: JavaScript (**400-499 순서 사용, 기능별 분리 시 순차 번호**)
   - 500: AJAX
   - 600: 데이터
   - 900: 푸터/초기화

### JavaScript 파일 분리 가이드

**분리해야 하는 경우:**
- JavaScript 파일이 300줄 이상
- 5개 이상의 독립적인 기능이 포함
- 유지보수가 어려운 경우

**분리 방법:**
1. 메인 파일(`400-js-{page}.blade.php`)은 @include만 포함
2. 기능별 파일을 401부터 순차적으로 번호 부여
3. 각 파일은 하나의 기능 그룹만 담당
4. 전역 변수는 400번 파일에 별도 분리

**예시 구조:**
```
202-page-auth-signup/
├── 400-js-signup.blade.php (메인: @include + 통합함수 + 초기화)
├── 400-js-global-state.blade.php (전역 변수)
├── 401-js-country-loader.blade.php (국가 로드)
├── 402-js-name-validation.blade.php (이름 검증)
├── 403-js-nickname-validation.blade.php (닉네임 검증)
├── 404-js-phone-validation.blade.php (전화 검증)
├── 405-js-password-validation.blade.php (비밀번호 검증)
├── 406-js-email-handler.blade.php (이메일 처리)
├── 407-js-form-validation.blade.php (폼 검증)
└── 408-js-form-submit.blade.php (폼 제출)
```

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
- **JavaScript 분리 규칙**: 
  - 메인 파일은 400번 사용
  - 분리 파일은 401부터 순차적으로 사용
  - 기능별 명확한 네이밍 (validation, handler, loader, setup 등)
  - 모든 JavaScript 파일은 반드시 .blade.php 확장자 사용

