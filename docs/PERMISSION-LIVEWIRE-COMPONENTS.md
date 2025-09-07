# 권한 관리 Livewire 컴포넌트 시스템

## 개요

Spatie Laravel Permission 기반의 동적 권한 시스템과 통합된 모듈형 Livewire 컴포넌트 시스템입니다.

## 컴포넌트 구조

### 1. 메인 컨테이너 (920-livewire-permission-management.blade.php)
- 탭 기반 네비게이션
- 4개의 주요 섹션으로 구성
- Alpine.js 기반 상태 관리

### 2. 서브 컴포넌트들

#### PermissionOverview (921-permission-overview.blade.php)
- **기능**: 시스템 현황 대시보드
- **표시 내용**: 
  - 전체 역할/권한/동적규칙 통계
  - 권한 매트릭스 (역할별 권한 현황)
  - 최근 변경사항 타임라인
- **실시간 업데이트**: 새로고침 버튼으로 데이터 리로드

#### RoleManagement (922-role-management.blade.php) 
- **기능**: 역할 관리 (CRUD)
- **주요 기능**:
  - 역할 생성/수정/삭제
  - 권한 할당/해제
  - 역할별 사용자 수 표시
  - 역할 레벨별 정렬
- **모달**: 생성/편집/삭제 확인

#### PermissionCategoryManagement (923-permission-category-management.blade.php)
- **기능**: 권한 및 카테고리 관리
- **주요 기능**:
  - 권한 생성/수정/삭제
  - 카테고리별 권한 분류
  - 권한 카테고리 관리
  - 역할별 권한 할당 현황
- **분류 기준**: member, project, billing, organization, permission

#### DynamicRuleManagement (924-dynamic-rule-management.blade.php)
- **기능**: 동적 권한 규칙 관리
- **주요 기능**:
  - 동적 규칙 생성/수정/삭제/테스트
  - 리소스 타입별 액션 정의
  - 커스텀 JSON 로직 지원
  - 우선순위 및 활성화 상태 관리
  - 실시간 권한 테스트 기능

## Filament 통합

기존 Filament Resources와의 연동:
- `PermissionResource`: 기본 권한 관리
- `PermissionCategoryResource`: 권한 카테고리 관리  
- `DynamicPermissionRuleResource`: 동적 규칙 관리

Livewire 컴포넌트는 일반 사용자 인터페이스를 위한 것이며, Filament는 관리자 백엔드 인터페이스로 병행 사용됩니다.

## 데이터 플로우

1. **DynamicPermissionService**: 중앙집중식 권한 처리
2. **Spatie Models**: Role, Permission 모델 사용
3. **Custom Models**: PermissionCategory, DynamicPermissionRule
4. **Cache Management**: 권한 규칙 변경 시 자동 캐시 클리어

## 사용 방법

### 1. 라우트 설정
```php
Route::get('/admin/permissions', function() {
    return view('900-page-platform-admin.920-livewire-permission-management');
})->middleware('permission:manage permissions');
```

### 2. 컴포넌트 로드
```blade
<div>
    @livewire('organization.admin.permission-management')
</div>
```

### 3. 탭별 컴포넌트
- overview: `@livewire('platform-admin.permission-overview')`
- roles: `@livewire('platform-admin.role-management')`  
- permissions: `@livewire('platform-admin.permission-category-management')`
- rules: `@livewire('platform-admin.dynamic-rule-management')`

## 보안 고려사항

1. **권한 검증**: 모든 컴포넌트에서 적절한 권한 체크
2. **입력 검증**: 폼 데이터 유효성 검사
3. **XSS 방지**: 사용자 입력 이스케이핑
4. **CSRF 보호**: Livewire 기본 CSRF 보호 활용
5. **커스텀 로직 안전성**: JSON 기반 제한적 로직만 허용

## 확장 가능성

- 새로운 권한 카테고리 추가 가능
- 커스텀 동적 규칙 조건 확장 가능
- UI 컴포넌트 재사용 및 커스터마이징 가능
- Filament과의 완전한 데이터 동기화

## 파일 구조

```
app/Livewire/
├── Organization/Admin/
│   └── PermissionManagement.php          # 메인 컴포넌트
└── PlatformAdmin/
    ├── PermissionOverview.php             # 개요 컴포넌트
    ├── RoleManagement.php                 # 역할 관리
    ├── PermissionCategoryManagement.php   # 권한/카테고리 관리
    └── DynamicRuleManagement.php          # 동적 규칙 관리

resources/views/900-page-platform-admin/
├── 920-livewire-permission-management.blade.php  # 메인 뷰
└── components/
    ├── 921-permission-overview.blade.php
    ├── 922-role-management.blade.php
    ├── 923-permission-category-management.blade.php
    └── 924-dynamic-rule-management.blade.php
```

이 시스템은 완전히 모듈화되어 있어 각 컴포넌트를 독립적으로 사용하거나 확장할 수 있습니다.