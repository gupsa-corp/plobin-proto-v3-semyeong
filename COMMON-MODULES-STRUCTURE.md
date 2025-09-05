# 공통 모듈 구조 가이드

Plobin Proto V3 프로젝트의 공통 모듈 구조와 사용법에 대한 문서입니다.

## 🎯 최신 구조 (2024.09 업데이트)

### 📁 300-page-service 새로운 구조

**📋 설계 원칙**:
- **1파일 1함수**: 모든 JavaScript 함수는 개별 파일로 분리
- **AJAX 메소드별 분리**: GET, POST, PUT, DELETE 각각 독립 파일
- **번호 체계**: 000(인증), 100(헤더), 200(사이드바), 300(레이아웃/모달), 400(JS), 500(AJAX), 600(데이터)
- **재활용성 강화**: 공통 구조와 페이지별 컨텐츠 완전 분리

```
300-page-service/
├── 300-common/                           # 공통 모듈 (모든 페이지에서 재활용)
│   ├── 000-auth-token-manager.blade.php  # 토큰 관리 공통 함수
│   ├── 100-header-main.blade.php         # 메인 헤더 구조
│   ├── 102-header-breadcrumb.blade.php   # 브레드크럼
│   ├── 103-header-user-dropdown.blade.php # 사용자 드롭다운
│   ├── 104-header-alarm.blade.php        # 알림 버튼
│   ├── 105-header-settings.blade.php     # 설정 버튼
│   ├── 106-header-mobile-menu.blade.php  # 모바일 메뉴 버튼
│   ├── 107-header-right-menu.blade.php   # 우측 메뉴 통합
│   ├── 200-sidebar-main.blade.php        # 메인 사이드바 구조
│   ├── 201-sidebar-navigation.blade.php  # 네비게이션 메뉴
│   ├── 202-sidebar-organization-info.blade.php # 조직 선택 영역
│   ├── 301-layout-head.blade.php         # HTML Head 레이아웃
│   ├── 302-layout-css-imports.blade.php  # CSS 임포트 관리
│   ├── 303-layout-js-imports.blade.php   # JS 임포트 관리
│   ├── 500-ajax-get.blade.php            # AJAX GET 요청 함수
│   ├── 500-ajax-post.blade.php           # AJAX POST 요청 함수
│   ├── 500-ajax-put.blade.php            # AJAX PUT 요청 함수
│   ├── 500-ajax-delete.blade.php         # AJAX DELETE 요청 함수
│   └── 900-alpine-init.blade.php         # Alpine.js 초기화
├── 301-page-dashboard/                    # 대시보드 페이지
│   ├── 000-index.blade.php               # 페이지 인덱스
│   ├── 200-content-*.blade.php           # 콘텐츠 블록들
│   ├── 300-modal-*.blade.php            # 모달 컴포넌트들
│   ├── 400-js-*.blade.php               # JavaScript 함수들 (1파일 1함수)
│   ├── 500-ajax-*.blade.php             # AJAX 함수들 (메소드별 분리)
│   └── 600-data-sidebar.blade.php        # 페이지별 사이드바 데이터
└── 302-page-organization-dashboard/       # 조직 대시보드 페이지
    ├── 000-index.blade.php               # 페이지 인덱스
    ├── 200-content-main.blade.php        # 메인 콘텐츠
    ├── 300-modal-organization-manager.blade.php # 조직 관리 모달
    ├── 400-js-*.blade.php               # JavaScript 함수들 (1파일 1함수)
    ├── 500-ajax-*.blade.php             # AJAX 함수들 (메소드별 분리)
    └── 600-data-sidebar.blade.php        # 페이지별 사이드바 데이터
```

## 1. JavaScript 공통 모듈

### 📁 기존 위치: `resources/views/000-common-javascript/` (레거시)

#### 🔧 API 관련 모듈

**`api.error-handler.blade.php`**
- **기능**: API 오류 처리 통합 관리
- **클래스**: `ApiErrorHandler`
- **주요 메서드**:
  - `handle(error, context)` - 오류 통합 처리
  - `is401Error(error)` - 401 오류 확인
  - `handleUnauthorized()` - 인증 실패 처리
- **사용처**: 모든 API 호출이 있는 페이지

**`ajax.api-client.blade.php`**
- **기능**: HTTP 요청 공통 유틸리티
- **클래스**: `ApiClient`
- **주요 메서드**:
  - `get(url, options)` - GET 요청
  - `post(url, data, options)` - POST 요청
  - `put(url, data, options)` - PUT 요청
  - `delete(url, options)` - DELETE 요청
- **특징**: 자동 토큰 헤더 추가, 오류 처리

#### 🔐 인증 관련 모듈

**`auth.authentication-manager.blade.php`**
- **기능**: 사용자 인증 상태 관리
- **클래스**: `AuthenticationManager`
- **주요 메서드**:
  - `checkAuth()` - 인증 상태 확인
  - `showDashboard(userData)` - 대시보드 표시
  - `updateUserInfo(userData)` - 사용자 정보 업데이트
  - `logout()` - 로그아웃 처리
- **사용처**: 로그인이 필요한 모든 서비스 페이지

#### 🎨 UI 관련 모듈

**`view.modal-utils.blade.php`**
- **기능**: 모달 관리 공통 유틸리티
- **클래스**: `ModalUtils`
- **주요 메서드**:
  - `showModal(modalId)` - 모달 표시
  - `hideModal(modalId)` - 모달 숨김
  - `setupBackdropClose(modalId)` - 배경 클릭 시 닫기
  - `setupEscapeClose(modalIds)` - ESC 키로 닫기
  - `clearModalInputs(modalId)` - 입력 필드 초기화
- **사용처**: 모달이 있는 모든 페이지

**`ui.dashboard-sidebar.blade.php`**
- **기능**: 대시보드 사이드바 Alpine.js 컴포넌트
- **함수**: `dashboardSidebar()`
- **주요 기능**:
  - 조직 목록 로드 및 관리
  - 조직 선택 드롭다운
  - 조직 생성 모달
  - 모바일 사이드바 토글
  - 네비게이션 상태 관리
- **사용처**: 3xx 서비스 페이지 (대시보드, 조직 대시보드)

**`modal.organization-manager.blade.php`**
- **기능**: 조직 생성 모달 전용 관리자
- **클래스**: `OrganizationModalManager`
- **주요 메서드**:
  - `showCreateModal()` - 생성 모달 표시
  - `createOrganization()` - 조직 생성 API 호출
  - `showSuccessModal(orgName)` - 성공 모달 표시
  - `setupEventListeners()` - 이벤트 리스너 설정
- **사용처**: 조직 생성 기능이 있는 페이지

#### 📦 전체 로더

**`index.blade.php`**
- **기능**: 모든 공통 모듈을 한 번에 로드
- **포함 모듈**: API, AJAX, Auth, View, Modal 모듈 전체
- **사용법**: `@include('000-common-javascript.index')`

### 🎯 명명 규칙

```
{카테고리}.{기능명}.blade.php
```

#### 카테고리별 분류
- **`api.`** - API 오류 처리, 상태 관리
- **`ajax.`** - HTTP 요청, 통신 관련
- **`auth.`** - 인증, 권한 관리
- **`view.`** - 일반적인 UI 컴포넌트
- **`modal.`** - 특정 모달 전용 기능

## 2. 새로운 AJAX 구조 (500번대)

### 📋 AJAX 메소드별 분리 원칙

#### 🌐 공통 AJAX 함수 (`300-common/500-ajax-*.blade.php`)
- **`500-ajax-get.blade.php`** - 범용 GET 요청 함수 `ajaxGet(url, options)`
- **`500-ajax-post.blade.php`** - 범용 POST 요청 함수 `ajaxPost(url, data, options)`
- **`500-ajax-put.blade.php`** - 범용 PUT 요청 함수 `ajaxPut(url, data, options)`
- **`500-ajax-delete.blade.php`** - 범용 DELETE 요청 함수 `ajaxDelete(url, options)`

#### 🎯 페이지별 AJAX 함수 예시 (`302-page-organization-dashboard/500-ajax-*.blade.php`)
- **`500-ajax-organization-detail-get.blade.php`** - 조직 상세 정보 조회
- **`500-ajax-organization-stats-get.blade.php`** - 대시보드 통계 조회
- **`500-ajax-organization-activities-get.blade.php`** - 최근 활동 조회
- **`500-ajax-organization-projects-get.blade.php`** - 최근 프로젝트 조회
- **`500-ajax-organization-members-get.blade.php`** - 멤버 목록 조회
- **`500-ajax-organization-invite-post.blade.php`** - 멤버 초대 (POST)

### 📱 JavaScript 함수 구조 (400번대)

#### 🔧 공통 유틸리티 함수 (`302-page-organization-dashboard/400-js-*.blade.php`)
- **`400-js-org-get-auth-headers.blade.php`** - 인증 헤더 생성 함수
- **`400-js-org-get-default-stats.blade.php`** - 기본 통계 데이터 함수
- **`400-js-org-get-default-activities.blade.php`** - 기본 활동 데이터 함수
- **`400-js-org-get-default-projects.blade.php`** - 기본 프로젝트 데이터 함수

#### 📊 데이터 로딩 함수 (`302-page-organization-dashboard/401-js-*.blade.php`)
- **`401-js-org-dashboard-load-all-data.blade.php`** - 모든 데이터 로딩 함수
- **`401-js-org-dashboard-load-organization-data.blade.php`** - 조직 데이터 로딩 함수

## 3. Layout 공통 컴포넌트

### 📁 위치별 분류

#### 🏠 Landing 페이지 (`100-landing-common/`)
- **`header.blade.php`** - 랜딩 페이지 헤더 (로그인/회원가입 링크)
- **`footer.blade.php`** - 랜딩 페이지 푸터
- **`head.blade.php`** - 랜딩 페이지용 메타 태그, CSS

#### 🔐 인증 페이지 (`200-auth-common/`)
- **`header.blade.php`** - 인증 페이지 헤더 (로고만)
- **`footer.blade.php`** - 인증 페이지 푸터
- **`head.blade.php`** - 인증 페이지용 메타 태그, CSS

#### 🏢 서비스 페이지 (`300-common/`) - 새로운 구조
- **`100-header-main.blade.php`** - 메인 헤더 구조 (통합)
- **`102-header-breadcrumb.blade.php`** - 브레드크럼 (세분화)
- **`103-header-user-dropdown.blade.php`** - 사용자 드롭다운 (세분화)
- **`104-header-alarm.blade.php`** - 알림 버튼 (세분화)
- **`105-header-settings.blade.php`** - 설정 버튼 (세분화)
- **`200-sidebar-main.blade.php`** - 메인 사이드바 구조 (통합)
- **`201-sidebar-navigation.blade.php`** - 네비게이션 메뉴 (세분화)
- **`202-sidebar-organization-info.blade.php`** - 조직 선택 영역 (세분화)

#### 👨‍💼 관리자 페이지 (`900-admin-common/`)
- **`header.blade.php`** - 관리자 헤더
- **`footer.blade.php`** - 관리자 푸터
- **`sidebar.blade.php`** - 관리자 사이드바
- **`head.blade.php`** - 관리자 페이지용 메타 태그, CSS

### 📄 Content 영역 공통 컴포넌트

#### 📊 대시보드 (`301-service-dashboard/`)
- **`main-dashboard.blade.php`** - 메인 대시보드 컨텐츠
- **`organization-selection.blade.php`** - 조직 선택 영역
- **`dashboard-data.blade.php`** - 대시보드 데이터 표시

#### 🏢 조직 대시보드 (`302-service-organization-dashboard/`)
- **`sidebar-data.blade.php`** - 조직 사이드바 데이터

## 4. Modal & Content 구조

### 📁 새로운 Modal/Content 명명 규칙

```
3xx-service-{페이지명}-modal-{00x}-{모달명}/
├── modal.blade.php

3xx-service-{페이지명}-content-{00x}-{컨텐츠명}/
├── content.blade.php
```

### 🎨 Modal 컴포넌트

#### 301-service-dashboard 모달들
- **`301-service-dashboard-modal-001-create-organization/`** - 조직 생성 모달
- **`301-service-dashboard-modal-002-create-organization-success/`** - 생성 성공 모달  
- **`301-service-dashboard-modal-003-organization-manager/`** - 조직 관리 모달

#### 302-service-organization-dashboard 모달들
- **`302-service-organization-dashboard-modal-001-organization-manager/`** - 조직 관리 모달

### 📦 Content 컴포넌트

#### 301-service-dashboard 컨텐츠들
- **`301-service-dashboard-content-001-auth-check/`** - 인증 체크 블록

### 🔄 마이그레이션 완료 항목

#### ❌ 폐지된 구조 (4xx, 5xx)
- ~~`401-service-modal-dashboard/`~~ → `301-service-dashboard-modal-003-organization-manager/`
- ~~`402-service-modal-organization-dashboard/`~~ → `302-service-organization-dashboard-modal-001-organization-manager/`
- ~~`501-service-block-auth-check/`~~ → `301-service-dashboard-content-001-auth-check/`

#### ✅ 정리된 구조
- ~~`301-service-dashboard/modal-create-organization.blade.php`~~ → `301-service-dashboard-modal-001-create-organization/modal.blade.php`
- ~~`301-service-dashboard/modal-create-organization-success.blade.php`~~ → `301-service-dashboard-modal-002-create-organization-success/modal.blade.php`

### 📨 이메일 템플릿 (`emails/`)
- **`auth/reset-password.blade.php`** - 비밀번호 재설정 이메일

## 5. 사용법 가이드

### 📝 개별 모듈 사용

```blade
{{-- API 오류 처리만 필요한 경우 --}}
@include('000-common-javascript.api.error-handler')

{{-- 모달 유틸리티만 필요한 경우 --}}
@include('000-common-javascript.view.modal-utils')
```

### 📦 전체 모듈 사용

```blade
{{-- 모든 공통 JavaScript 모듈 로드 --}}
@include('000-common-javascript.index')
```

### 🎨 Modal/Content 컴포넌트 사용

```blade
{{-- 모달 사용 --}}
@include('301-service-dashboard-modal-001-create-organization.modal')
@include('302-service-organization-dashboard-modal-001-organization-manager.modal')

{{-- 컨텐츠 사용 --}}
@include('301-service-dashboard-content-001-auth-check.content')
```

### 🔧 기존 파일 공통 모듈 적용

```blade
{{-- 기존: 중복 코드 --}}
<script>
class ApiErrorHandler {
  // 중복된 코드...
}
</script>

{{-- 개선: 공통 모듈 사용 --}}
@include('000-common-javascript.api.error-handler')
```

## 6. 확장 가이드

### 🆕 새로운 공통 모듈 추가

1. **적절한 카테고리 선택**
   - `api.` - API 관련
   - `ajax.` - HTTP 통신 관련
   - `auth.` - 인증 관련
   - `view.` - 일반 UI 관련
   - `modal.` - 모달 관련

2. **파일명 규칙 준수**
   ```
   {카테고리}.{기능명}.blade.php
   ```

3. **클래스 구조 일관성 유지**
   ```javascript
   {{-- 설명 주석 --}}
   <script>
   /**
    * 기능 설명
    */
   class ClassName {
       // 구현...
   }
   </script>
   ```

### 🔄 기존 중복 코드 공통화

1. **중복 코드 식별**
2. **공통 모듈로 추출**
3. **기존 파일에서 include로 대체**
4. **테스트 및 검증**

## 7. 주의사항

### ⚠️ 금지사항 (CLAUDE.md 준수)
- 포트 번호 명시 금지
- 불필요한 새 파일 생성 금지
- 기존 기능 중복 구현 금지

### ✅ 권장사항
- 기존 파일 편집 우선
- 공통 모듈 재사용
- 명명 규칙 준수
- 문서화 유지

## 8. 현재 적용 현황

### ✅ 공통 모듈로 변경 및 정리 완료

#### 삭제된 중복 파일들
- ~~`301-service-dashboard/js/ApiErrorHandler.blade.php`~~ → `000-common-javascript/api.error-handler.blade.php`
- ~~`302-service-organization-dashboard/js/ApiErrorHandler.blade.php`~~ → `000-common-javascript/api.error-handler.blade.php`  
- ~~`301-service-dashboard/js/OrganizationModalManager.blade.php`~~ → `000-common-javascript/modal.organization-manager.blade.php`
- ~~`301-service-dashboard/js/AuthenticationManager.blade.php`~~ → `000-common-javascript/auth.authentication-manager.blade.php`

#### 업데이트된 include 파일들
- `301-service-dashboard/javascript.blade.php` - 공통 모듈 include로 변경
- `302-service-organization-dashboard/javascript.blade.php` - 공통 모듈 include로 변경

### ✅ Modal/Content 구조 정리 완료

#### 새로 정리된 구조
- **`301-service-dashboard-modal-001-create-organization/`** - 조직 생성 모달
- **`301-service-dashboard-modal-002-create-organization-success/`** - 생성 성공 모달
- **`301-service-dashboard-modal-003-organization-manager/`** - 조직 관리 모달
- **`302-service-organization-dashboard-modal-001-organization-manager/`** - 조직 관리 모달
- **`301-service-dashboard-content-001-auth-check/`** - 인증 체크 컨텐츠

#### 폐지된 4xx, 5xx 구조
- ~~`401-service-modal-dashboard/`~~ - 완전 삭제
- ~~`402-service-modal-organization-dashboard/`~~ - 완전 삭제
- ~~`501-service-block-auth-check/`~~ - 완전 삭제

### 📋 향후 공통화 대상
- 유사한 대시보드 컴포넌트들
- 반복되는 폼 validation 로직
- 공통 데이터 처리 함수들
- 추가 모달 패턴들

### 🎯 정리 효과
- **중복 코드 제거**: 7개 중복/혼재 파일 완전 제거
- **구조 통일**: 모든 모달이 일관된 명명 규칙 사용
- **유지보수성 향상**: 각 페이지별로 모달이 명확히 구분됨
- **파일 구조 정리**: 4xx, 5xx 폐지로 프로젝트 구조 간소화
