# 공통 모듈 구조 가이드

Plobin Proto V3 프로젝트의 공통 모듈 구조와 사용법에 대한 문서입니다.

## 1. JavaScript 공통 모듈

### 📁 위치: `resources/views/000-common-javascript/`

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

## 2. Layout 공통 컴포넌트

### 📁 위치별 분류

#### 🏠 Landing 페이지 (`100-landing-common/`)
- **`header.blade.php`** - 랜딩 페이지 헤더 (로그인/회원가입 링크)
- **`footer.blade.php`** - 랜딩 페이지 푸터
- **`head.blade.php`** - 랜딩 페이지용 메타 태그, CSS

#### 🔐 인증 페이지 (`200-auth-common/`)
- **`header.blade.php`** - 인증 페이지 헤더 (로고만)
- **`footer.blade.php`** - 인증 페이지 푸터
- **`head.blade.php`** - 인증 페이지용 메타 태그, CSS

#### 🏢 서비스 페이지 (`300-service-common/`)
- **`header.blade.php`** - 서비스 헤더 (사용자 정보, 메뉴)
- **`sidebar.blade.php`** - 서비스 사이드바 (조직 선택, 네비게이션)
- **`logo.blade.php`** - 로고 컴포넌트
- **`head.blade.php`** - 서비스 페이지용 메타 태그, CSS

#### 🔧 서비스 헤더 에셋 (`300-service-common-header-assets/`)
- **`header-assets-user-dropdown.blade.php`** - 사용자 드롭다운 메뉴
- **`header-assets-user-button.blade.php`** - 사용자 버튼
- **`header-assets-breadcrumb.blade.php`** - 브레드크럼브
- **`header-assets-right-menu.blade.php`** - 우측 메뉴
- **`ajax-user-dropdown.blade.php`** - 사용자 드롭다운 AJAX

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
