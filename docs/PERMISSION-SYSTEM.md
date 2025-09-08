# 동적 권한 시스템

## 개요

기존 하드코딩된 권한 시스템을 완전 동적 관리 가능한 시스템으로 교체
Spatie Laravel Permission 패키지 기반으로 DB에서 모든 권한 규칙 관리

## 주요 변경사항

기존 OrganizationPermission enum 기반에서 DB 기반 동적 시스템으로 전환
- 하드코딩 완전 제거
- GUI에서 실시간 권한 생성/수정/삭제 
- 복잡한 권한 로직 동적 설정
- 기존 시스템과 호환성 유지

## 핵심 구성요소

### DynamicPermissionService
중앙집중식 권한 체크 서비스
- canPerformAction: 동적 권한 규칙 기반 체크
- assignBasicPermissions: 기존 enum 레벨을 역할로 변환
- 캐시 관리 및 성능 최적화

### 동적 권한 규칙 (DynamicPermissionRule)
DB에서 관리되는 권한 규칙
- 리소스 타입별 액션 정의
- 필수 권한/역할 조건 설정
- 커스텀 로직 지원
- 실시간 활성화/비활성화

### 권한 카테고리 시스템
권한을 체계적으로 분류
- 회원 관리 (member_management)
- 프로젝트 관리 (project_management) 
- 결제 관리 (billing_management)
- 조직 설정 (organization_settings)
- 권한 관리 (permission_management)

## 사용법

### 기존 방식 (여전히 작동)
기존 코드 호환성 유지
- $user->organizationMemberships->role_name === 'organization_admin'
- role_name 기반 권한 체크

### 새로운 동적 방식
완전 동적 권한 체크
- $user->canPerform('member_management', 'invite')
- $user->can('invite members')

### User 모델 확장 메소드
편의성을 위한 추가 메소드
- canPerform: 동적 권한 체크
- hasOrganizationPermission: 기존 시스템과 호환
- getPermissionSummary: 사용자 권한 요약

## 관리자 인터페이스

### 역할 관리 (RoleResource)
Filament 관리 패널에서 역할 관리
- 역할 생성/수정/삭제
- 권한 할당/해제
- 사용자별 역할 현황

### 동적 권한 규칙 관리 (DynamicPermissionRuleResource)  
하드코딩 없는 권한 규칙 설정
- 리소스 타입별 액션 정의
- 필수 권한/역할 조건
- 커스텀 로직 JSON 설정
- 실시간 활성화 제어

### 권한 카테고리 관리 (PermissionCategoryResource)
권한 분류 체계 관리
- 새로운 카테고리 추가
- 표시명 및 설명 설정
- 정렬 순서 관리

## 마이그레이션 전략

### 단계적 전환
기존 시스템 유지하며 점진적 전환
- 기존 OrganizationMember 테이블 유지
- 새로운 Spatie 테이블 추가 생성
- 양방향 호환성 보장

### 데이터 변환
SpatiePermissionSeeder로 기초 데이터 생성
- 기본 역할 및 권한 생성
- 동적 규칙 초기 설정
- 권한 템플릿 제공

## 활용 예시

### 새로운 권한 규칙 생성
관리자 패널에서 GUI로 생성
- 리소스: project_management
- 액션: super_create
- 필수 권한: create projects, manage system settings
- 설명: 특별 프로젝트 생성 권한

### 복잡한 조건 설정
JSON 기반 커스텀 로직
- AND/OR 조건 조합
- 컨텍스트 기반 권한 체크
- 동적 조건 평가

## 주의사항

### 캐시 관리
권한 규칙 변경시 자동 캐시 클리어
- DynamicPermissionService에서 자동 처리
- 성능 최적화를 위한 캐싱
- 실시간 권한 변경 반영

### 보안 고려사항
커스텀 로직 실행시 보안 주의
- eval() 대신 JSON 기반 조건 파싱
- 제한된 조건 타입만 허용
- 로그 기록 및 오류 처리

### 호환성 유지
role_name + Spatie Permission 시스템으로 통일
- OrganizationMember.role_name 컬럼 사용
- Spatie Permission 기반 체크
- Laravel 표준 권한 시스템 활용

권한 관리가 완전 동적으로 변경되어 유연성과 확장성이 대폭 향상됨