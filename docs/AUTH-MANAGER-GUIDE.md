# 중앙집중식 AuthManager 사용법

## 개요

기존의 분산된 인증 관리를 중앙집중식으로 개선한 AuthManager 클래스

## 주요 변경사항

기존 4개 파일에 분산됐던 인증 로직을 단일 클래스로 통합
- 코드 중복 제거
- 일관된 에러 처리
- 이벤트 기반 반응형 시스템
- 단일 방식 사용 (레거시 함수 제거)

## 파일 위치

- AuthManager: 300-page-service/300-common/005-auth-manager.blade.php
- ApiClient: 300-page-service/300-common/006-api-client.blade.php

## 주요 기능

### 로그인 처리
- window.AuthManager.login(email, password, remember)

### 로그아웃 처리  
- window.AuthManager.logout('/login')

### 인증 상태 확인
- window.AuthManager.isAuthenticated()
- window.AuthManager.getUser()

### API 요청
- window.AuthManager.makeRequest(url, options)
- window.apiClient.get(endpoint, params)
- window.apiClient.post(endpoint, data)
- window.apiClient.put(endpoint, data)
- window.apiClient.delete(endpoint)
- 자동 헤더 관리 및 401 처리

### 유효성 검사
- window.AuthManager.validateLoginForm(email, password)
- window.AuthManager.validateEmail(email)

## 이벤트 시스템

사용 가능한 이벤트:
- tokenSet: 토큰 설정시
- tokenRemoved: 토큰 제거시  
- userLoaded: 사용자 정보 로드시
- userUpdated: 사용자 정보 업데이트시
- loginSuccess: 로그인 성공시
- logoutSuccess: 로그아웃 성공시

## Alpine.js 통합

사용자 드롭다운에서 AuthManager 이벤트 연결해서 반응형 UI 구현 가능

## 주의사항

- AuthManager는 다른 스크립트보다 먼저 로드돼야 함
- AuthManager 방식만 사용 (레거시 함수 제거됨)
- 토큰 유효성 자동 검증 및 만료시 자동 로그아웃
- 401 에러 자동 처리

인증 관리가 중앙집중화되어 코드 재사용성 향상 및 유지보수 개선됨
