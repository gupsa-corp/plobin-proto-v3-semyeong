# Plobin Proto V3 개발 가이드

## 최우선 준수 규칙

### 파일 구조 및 URL 설계 규칙
**modal, dropdown, table, block 급 컴포넌트는 무조건 파일 분리**
- 올바른 예: `200-modal-user-edit.blade.php`, `200-dropdown-menu.blade.php`, `200-table-users.blade.php`
- 잘못된 예: 큰 파일 안에 모달, 드롭다운, 테이블 코드 섞어 놓기
- **원칙**: 재사용 가능한 모든 UI 컴포넌트는 독립 파일로 분리

**page 급은 무조건 폴더별로 분리**
- 올바른 예: `903-page-users/000-index.blade.php`, `903-page-users/100-header-main.blade.php`
- 잘못된 예: 하나의 파일에 전체 페이지 구조 작성
- **원칙**: 각 페이지는 독립된 폴더에 header, sidebar, content 등으로 분리

**탭이 들어가는 화면은 무조건 페이지 URL을 분기처리해서 web.php에 경로 추가**
- 올바른 예: `/admin/users/overview`, `/admin/users/roles`, `/admin/users/permissions`
- 잘못된 예: 하나의 URL에서 JavaScript로만 탭 처리
- **원칙**: 각 탭마다 고유 URL과 라우트를 가져야 함 (SEO, 북마크, 뒤로가기 지원)

### 기술 스택 제한
**순수 JavaScript 사용 금지**
사용 금지: Vanilla JS, jQuery, Alpine.js의 복잡한 로직
사용 필수: Livewire + Filament 조합만 사용
모든 상호작용과 동적 기능은 다음으로만 구현:
- Livewire: 서버사이드 상태관리, 이벤트 처리
- Filament: UI 컴포넌트, 폼, 테이블 등
- 간단한 Alpine.js: 토글, 드롭다운 등 최소한의 UI 상호작용만

JavaScript가 필요한 경우 → Livewire로 재작성 필수
복잡한 UI가 필요한 경우 → Filament 컴포넌트 사용

## 프론트엔드 개발 규칙

### 파일 구조 및 네이밍
**절대 원칙**: 모든 프론트엔드 파일은 **무조건** 숫자 접두사 사용
- 올바른 예: `700-page-dashboard.blade.php`, `301-layout-head.blade.php`
- 잘못된 예: `dashboard.blade.php`, `head.blade.php`
- 폴더도 동일: `700-page-sandbox/`, `300-common/`
- 절대 금지: `components/`, `layouts/`, `pages/`, `livewire/`

### 뷰 파일 규칙
- 메인 페이지: `000-index.blade.php`
- 레이아웃: `301-layout-head.blade.php`
- 컴포넌트: `200-sidebar-main.blade.php`
- Livewire 뷰: `700-livewire-component-name.blade.php`

### 숫자 접두사 체계
- `000-xxx.blade.php`: 인덱스 파일 (메인 레이아웃)
- `100-xxx.blade.php`: 헤더 관련 파일들
- `200-xxx.blade.php`: 메인 콘텐츠, 사이드바 파일들
- `300-xxx.blade.php`: 레이아웃, 모달 파일들
- `400-xxx.blade.php`: JavaScript 파일들 (**필수: 400번대 사용**)
- `500-xxx.blade.php`: AJAX 요청 파일들
- `600-xxx.blade.php`: 데이터 관련 파일들
- `900-xxx.blade.php`: 초기화, 푸터 파일들

### 디렉토리 구조
```
resources/views/
├── 100-page-landing/           # 랜딩페이지 (100-199)
├── 200-page-auth/             # 인증 페이지 (200-299)
├── 300-page-service/          # 본 서비스 (300-399)
├── 700-page-sandbox/          # 샌드박스 (700-799)
├── 800-page-organization-admin/ # 조직 관리자 (800-899)
└── 900-page-platform-admin/    # 플랫폼 관리자 (900-999)
```

### 금지된 경로
**절대 금지**: `views/livewire/*` 경로에 blade 파일 생성

## 백엔드 개발 규칙

### API 구조 및 네이밍
**절대 원칙**: 모든 백엔드 파일은 **폴더명/기능명/Controller.php** 구조 사용

**필수 파일명 규칙**
- **Controller.php** - 컨트롤러는 반드시 `Controller`로 명명
- **Request.php** - 요청 검증은 반드시 `Request`로 명명

**올바른 예시**
`app/Http/User/Create/Controller.php`
`app/Http/User/Create/Request.php`
`app/Http/AuthUser/CheckEmail/Controller.php`
`app/Http/AuthUser/CheckEmail/Request.php`

**금지사항**
`SimplifiedController.php`
`UserCreateController.php`
`CheckEmailController.php`
`CreateUserRequest.php`

## 데이터베이스 규칙

### 마이그레이션 규칙
- **기존 마이그레이션 수정 우선**: 새로운 remove/alter 마이그레이션 생성 대신 기존 create 마이그레이션을 직접 수정
- **불필요한 마이그레이션 금지**: 컬럼 추가, 제거가 필요하면 처음부터 해당 컬럼을 생성하지 않는 것이 원칙
- **깔끔한 히스토리 유지**: 개발 단계에서는 마이그레이션 히스토리를 깔끔하게 유지하기 위해 기존 파일 수정을 선호

## 개발 가이드라인

### 일반 규칙
- 서버가 실행되어있다고 가정하고 진행
- 구현하려는 기능이 이미 있는지 검토
- 말하지 않은 컴포넌트 내용 만들지 않고 내용 비움
- 임시 토큰은 더 이상 사용하지 않음 (개발/운영 구분 없이 실제 API만 사용)
- 프론트엔드는 alpine js와 blade 템플릿 사용중
- 사이드바와 사이드바 데이터는 각각 30x 페이지에서 관리하며 300-common에서는 중앙관리하지 않음
- **파일명 규칙 준수 필수**: 모든 뷰 파일은 숫자 접두사-설명 형식으로 명명
- 대시보드 페이지의 콘텐츠는 현재 기본 빈 상태로 설정되어 있으며, 실제 데이터와 기능은 개발자가 구현해야 함
- 요청받지 않은 구현 필요한 영역은 어떤 css도 div도 없이 '구현필요'라는 문자만 넣음
- **인증 시스템은 라라벨 기본 Auth + Livewire 사용**: 커스텀 AuthManager 제거됨, Auth::attempt() 등 표준 라라벨 인증 사용
- **샌드박스 파일 구조 통합**: 모든 샌드박스 관련 파일은 `resources/views/700-page-sandbox` 디렉토리 하위에 배치

### 주의사항
- **하드코딩 금지**: 실제 데이터나 예시 텍스트를 하드코딩하지 말 것
- **빈 상태 유지**: 요청받은 것만 만들 것. 요청받은 경우 빈 값으로 유지

## 문서화 규칙

### 기본 원칙
- **반말 사용**: 토큰 절약을 위해 반말로 작성
- **실용성 중심**: 실제 사용 가능한 내용만
- **기존 컨벤션 준수**: 변경 요청 없으면 기존 코드 규칙 따를 것

### 절대 금지사항
- **포트 번호 언급**: 환경별로 다르므로 "로컬 서버", "개발 서버" 등으로 대체
- **중복 문서 작성**: 기존 문서 있는지 먼저 확인
- **계층도/구조도**: 관계도, 플로우차트 등 작성하지 말 것
- **숫자 목록**: "1. 2. 3." 형식 사용 금지
- **코드 예제**: 코드 블록 넣지 말 것
- **커맨드 명령어**: 가급적 명령어 넣지 말 것
- **더미 값**: 요청 외 예시나 더미 데이터 금지
- **메타 정보**: 업데이트 날짜, 작성자, 참고자료 등 금지

## 참고 문서

### 개발 가이드라인
- [백엔드 API](docs/CODING-BACKEND-API.md)
- [백엔드 예외 처리](docs/CODING-BACKEND-EXCEPTION.md)
- [주석 작성 규칙](docs/CODING-COMMENT.md)
- [프론트엔드](docs/CODING-FRONTEND.md)
- [마이그레이션](docs/CODING-MIGRATION.md)
- [백엔드 테스트](docs/CODING-TEST-BACKEND.md)
- [E2E 테스트](docs/CODING-TEST-E2E.md)
- [문서화](docs/DOCUMENTATION.md)

### 시스템 사용법
- [샌드박스 가이드](docs/AI-SANDBOX-GUIDE.md)
- [ApiClient 가이드](docs/API-CLIENT-GUIDE.md)
- [AuthManager 가이드](docs/AUTH-MANAGER-GUIDE.md)
- [권한 시스템](docs/PERMISSION-SYSTEM.md)
- [권한 Livewire 컴포넌트](docs/PERMISSION-LIVEWIRE-COMPONENTS.md)

### 개발 규칙
- [Blade 파일 제약사항](docs/BLADE_FILE_RESTRICTIONS.md)
