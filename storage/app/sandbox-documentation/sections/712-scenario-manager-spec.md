# 📋 시나리오 관리 시스템 스펙 문서

## 🎯 개요

본 문서는 Jira 티켓과 유사한 계층적 시나리오 관리 시스템의 상세 스펙을 정의합니다. 사용자는 시나리오를 그룹별로 관리하고, 큰 목적 아래에 작은 목적들을 계층적으로 구성하며, 각 단계별로 최대 10단계까지 세부 작업을 쪼개서 관리할 수 있습니다.

## 🏗️ 시스템 아키텍처

### 계층적 구조 설계

```
Scenario Group (시나리오 그룹)
├── Main Scenario (메인 시나리오 - 큰 목적)
│   ├── Sub-scenario 1 (서브 시나리오 - 작은 목적)
│   │   ├── Step 1 (세부 단계)
│   │   ├── Step 2
│   │   ├── Step 3
│   │   └── ...
│   │   └── Step 10 (최대 10단계)
│   ├── Sub-scenario 2
│   │   └── Step 1, 2, 3...
│   └── Sub-scenario N
└── Main Scenario 2
    └── Sub-scenarios...
```

### 데이터베이스 설계

#### 1. scenario_groups (시나리오 그룹)
```sql
CREATE TABLE scenario_groups (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    color VARCHAR(7) DEFAULT '#3B82F6',
    icon VARCHAR(50) DEFAULT 'folder',
    sort_order INT DEFAULT 0,
    created_by BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
```

#### 2. scenarios (메인 시나리오)
```sql
CREATE TABLE scenarios (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    group_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    status ENUM('backlog', 'todo', 'in-progress', 'review', 'done', 'cancelled') DEFAULT 'todo',
    assignee_id BIGINT,
    reporter_id BIGINT,
    estimated_hours DECIMAL(5,2),
    actual_hours DECIMAL(5,2),
    due_date DATE,
    tags JSON,
    progress_percentage INT DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_by BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES scenario_groups(id) ON DELETE CASCADE,
    FOREIGN KEY (assignee_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
```

#### 3. sub_scenarios (서브 시나리오)
```sql
CREATE TABLE sub_scenarios (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    scenario_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('todo', 'in-progress', 'done', 'cancelled') DEFAULT 'todo',
    assignee_id BIGINT,
    estimated_hours DECIMAL(5,2),
    actual_hours DECIMAL(5,2),
    progress_percentage INT DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (scenario_id) REFERENCES scenarios(id) ON DELETE CASCADE,
    FOREIGN KEY (assignee_id) REFERENCES users(id) ON DELETE SET NULL
);
```

#### 4. scenario_steps (세부 단계)
```sql
CREATE TABLE scenario_steps (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    sub_scenario_id BIGINT NOT NULL,
    step_number INT NOT NULL CHECK (step_number BETWEEN 1 AND 10),
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('todo', 'in-progress', 'done', 'blocked') DEFAULT 'todo',
    assignee_id BIGINT,
    estimated_hours DECIMAL(4,2),
    actual_hours DECIMAL(4,2),
    dependencies JSON, -- 선행 단계 ID 배열
    attachments JSON, -- 첨부파일 정보
    completed_at TIMESTAMP NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sub_scenario_id) REFERENCES sub_scenarios(id) ON DELETE CASCADE,
    FOREIGN KEY (assignee_id) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_step_number (sub_scenario_id, step_number)
);
```

#### 5. scenario_comments (댓글)
```sql
CREATE TABLE scenario_comments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    scenario_id BIGINT,
    sub_scenario_id BIGINT,
    step_id BIGINT,
    user_id BIGINT NOT NULL,
    content TEXT NOT NULL,
    attachments JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (scenario_id) REFERENCES scenarios(id) ON DELETE CASCADE,
    FOREIGN KEY (sub_scenario_id) REFERENCES sub_scenarios(id) ON DELETE CASCADE,
    FOREIGN KEY (step_id) REFERENCES scenario_steps(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CHECK (
        (scenario_id IS NOT NULL AND sub_scenario_id IS NULL AND step_id IS NULL) OR
        (scenario_id IS NULL AND sub_scenario_id IS NOT NULL AND step_id IS NULL) OR
        (scenario_id IS NULL AND sub_scenario_id IS NULL AND step_id IS NOT NULL)
    )
);
```

## 🎨 UI/UX 설계

### 메인 대시보드
- **그룹별 시나리오 트리뷰**: 좌측 패널에 그룹별 계층 구조 표시
- **칸반보드**: 상태별 컬럼 (Backlog, Todo, In Progress, Review, Done)
- **간트차트**: 타임라인 기반 진행 상황 시각화
- **대시보드 메트릭스**: 진행률, 마감일, 우선순위별 통계

### 시나리오 상세 페이지 (Jira 티켓 스타일)
```
┌─────────────────────────────────────────────────────────────┐
│ [PROJ-123] 메인 시나리오 제목                          [편집] │
├─────────────────────────────────────────────────────────────┤
│ 📝 설명          │ 담당자 │ 우선순위 │ 상태 │ 마감일 │
│ 긴 설명 텍스트... │ 홍길동 │ 높음    │ 진행중 │ 25/01/15 │
├─────────────────────────────────────────────────────────────┤
│ 🏷️ 태그: frontend, urgent, api-integration                 │
├─────────────────────────────────────────────────────────────┤
│ 📊 진행률: ████████░░░░ 80%                                 │
├─────────────────────────────────────────────────────────────┤
│ 👥 서브 시나리오 (3개)                                      │
│ ├─ [SUB-1] 사용자 인증 구현 (완료)                         │
│ │  ├─ 단계 1: 로그인 폼 설계 (완료)                        │
│ │  ├─ 단계 2: API 연동 (완료)                              │
│ │  └─ 단계 3: 테스트 작성 (진행중)                         │
│ ├─ [SUB-2] 권한 관리 시스템 (진행중)                       │
│ │  ├─ 단계 1: 권한 모델 설계 (완료)                        │
│ │  ├─ 단계 2: 미들웨어 구현 (진행중)                       │
│ │  └─ 단계 3: 테스트 및 검증                               │
│ └─ [SUB-3] 세션 관리 (대기중)                               │
├─────────────────────────────────────────────────────────────┤
│ 💬 댓글 (5개)                                               │
│ ├─ 홍길동: API 연동 완료했습니다.                          │
│ ├─ 김철수: 테스트 케이스 추가 부탁드립니다.                 │
│ └─ [댓글 입력란]                                           │
└─────────────────────────────────────────────────────────────┘
```

### 주요 UI 컴포넌트

#### 1. 계층적 트리뷰
- 그룹 > 메인 시나리오 > 서브 시나리오 > 단계별 트리 구조
- 접기/펼치기 기능
- 진행률 표시 바
- 상태별 색상 구분

#### 2. 칸반보드
- 수평 스크롤 가능한 컬럼들
- 드래그 앤 드롭으로 상태 변경
- WIP (Work In Progress) 제한 표시
- 우선순위별 정렬

#### 3. 단계별 타임라인
```
메인 시나리오
├── 서브 시나리오 1
│   ├── 1단계 [완료] ────── 2시간
│   ├── 2단계 [진행중] ──── 4시간 (예상)
│   └── 3단계 [대기중] ──── 2시간
└── 서브 시나리오 2
    ├── 1단계 [완료] ────── 3시간
    └── 2단계 [대기중] ──── 5시간
```

#### 4. 필터링 및 검색
- 그룹별 필터
- 상태별 필터 (Backlog, Todo, In Progress, Done 등)
- 우선순위 필터 (Critical, High, Medium, Low)
- 담당자 필터
- 기간별 필터
- 텍스트 검색 (제목, 설명, 태그)

## 🔧 API 설계

### RESTful 엔드포인트

#### 그룹 관리
```
GET    /api/scenario-manager/groups           # 그룹 목록 조회
POST   /api/scenario-manager/groups           # 그룹 생성
GET    /api/scenario-manager/groups/{id}      # 그룹 상세 조회
PUT    /api/scenario-manager/groups/{id}      # 그룹 수정
DELETE /api/scenario-manager/groups/{id}      # 그룹 삭제
```

#### 시나리오 관리
```
GET    /api/scenario-manager/scenarios        # 시나리오 목록 조회 (필터 지원)
POST   /api/scenario-manager/scenarios        # 시나리오 생성
GET    /api/scenario-manager/scenarios/{id}   # 시나리오 상세 조회
PUT    /api/scenario-manager/scenarios/{id}   # 시나리오 수정
DELETE /api/scenario-manager/scenarios/{id}   # 시나리오 삭제
POST   /api/scenario-manager/scenarios/{id}/move # 시나리오 이동 (그룹 간)
```

#### 서브 시나리오 관리
```
GET    /api/scenario-manager/scenarios/{scenarioId}/sub-scenarios
POST   /api/scenario-manager/scenarios/{scenarioId}/sub-scenarios
GET    /api/scenario-manager/sub-scenarios/{id}
PUT    /api/scenario-manager/sub-scenarios/{id}
DELETE /api/scenario-manager/sub-scenarios/{id}
```

#### 단계 관리
```
GET    /api/scenario-manager/sub-scenarios/{subId}/steps
POST   /api/scenario-manager/sub-scenarios/{subId}/steps
GET    /api/scenario-manager/steps/{id}
PUT    /api/scenario-manager/steps/{id}
DELETE /api/scenario-manager/steps/{id}
POST   /api/scenario-manager/steps/{id}/complete   # 단계 완료 처리
POST   /api/scenario-manager/steps/reorder         # 단계 순서 변경
```

### WebSocket 실시간 업데이트
```javascript
// 실시간 상태 변경 알림
socket.on('scenario.updated', (data) => {
    // 시나리오 상태 변경 시 UI 업데이트
});

// 새로운 댓글 알림
socket.on('comment.created', (data) => {
    // 댓글 추가 시 UI 업데이트
});

// 진행률 변경 알림
socket.on('progress.updated', (data) => {
    // 진행률 바 업데이트
});
```

## 🔐 권한 시스템

### 역할 기반 접근 제어
- **Viewer**: 읽기 전용
- **Contributor**: 생성, 수정, 삭제 (자신의 항목만)
- **Manager**: 모든 항목 관리 + 사용자 할당
- **Admin**: 시스템 설정 + 모든 권한

### 세부 권한
- 그룹 생성/수정/삭제
- 시나리오 생성/수정/삭제
- 서브 시나리오 관리
- 단계별 할당 및 상태 변경
- 댓글 작성/수정/삭제
- 파일 첨부
- 보고서 열람

## 📊 보고 및 분석

### 대시보드 메트릭스
- 전체 진행률
- 마감일 준수율
- 우선순위별 분포
- 담당자별 워크로드
- 그룹별 진행 상황
- 시간 추정 vs 실제 소요시간 비교

### 보고서 유형
- 주간/월간 진행 보고서
- 개인별 성과 보고서
- 프로젝트별 상태 보고서
- 시간 추적 보고서
- 품질 메트릭스 보고서

## 🔄 워크플로우

### 표준 프로세스
1. **그룹 생성**: 프로젝트나 기능별 그룹 생성
2. **메인 시나리오 작성**: 큰 목적 정의
3. **서브 시나리오 분해**: 작은 목적들로 분할
4. **단계별 세부화**: 각 서브 시나리오를 1-10단계로 분해
5. **담당자 할당**: 각 단계별 담당자 지정
6. **진행 관리**: 상태 업데이트 및 진행률 관리
7. **완료 및 검토**: 단계별 완료 처리 및 검토

### 자동화 기능
- **의존성 관리**: 선행 단계 완료 시 다음 단계 자동 활성화
- **마감일 알림**: 마감일 임박 시 자동 알림
- **진행률 자동 계산**: 하위 단계 완료율 기반 상위 진행률 계산
- **워크플로우 템플릿**: 자주 사용하는 패턴 저장 및 재사용

## 🚀 기술 스택

### Backend
- Laravel 12.x (PHP 8.2+)
- MySQL 8.0+
- Redis (캐싱 및 실시간 기능)
- Laravel Sanctum (API 인증)

### Frontend
- Livewire 3.x (풀스택 컴포넌트)
- Alpine.js (클라이언트 인터랙션)
- Tailwind CSS (스타일링)
- Heroicons (아이콘)

### 실시간 기능
- Laravel Broadcasting
- Socket.io 또는 Laravel WebSockets
- Redis Pub/Sub

### 파일 관리
- Laravel Storage
- AWS S3 또는 로컬 파일시스템
- 파일 타입 검증 및 보안

## 🔧 Laravel 프로젝트 구조 통합

### 모델 및 관계 정의
```php
// app/Models/ScenarioGroup.php
class ScenarioGroup extends Model
{
    protected $fillable = ['name', 'description', 'color', 'icon', 'sort_order', 'created_by'];

    public function scenarios()
    {
        return $this->hasMany(Scenario::class)->orderBy('sort_order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

// app/Models/Scenario.php
class Scenario extends Model
{
    protected $fillable = [
        'group_id', 'title', 'description', 'priority', 'status',
        'assignee_id', 'reporter_id', 'estimated_hours', 'actual_hours',
        'due_date', 'tags', 'progress_percentage', 'sort_order', 'created_by'
    ];

    protected $casts = [
        'due_date' => 'date',
        'tags' => 'array',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2'
    ];

    public function group()
    {
        return $this->belongsTo(ScenarioGroup::class);
    }

    public function subScenarios()
    {
        return $this->hasMany(SubScenario::class)->orderBy('sort_order');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function comments()
    {
        return $this->morphMany(ScenarioComment::class, 'commentable');
    }
}
```

### Livewire 컴포넌트 구조
```
app/Livewire/ScenarioManager/
├── ScenarioManager.php              # 메인 컴포넌트
├── Components/
│   ├── ScenarioBoard.php           # 칸반보드 컴포넌트
│   ├── ScenarioTree.php            # 트리뷰 컴포넌트
│   ├── ScenarioDetail.php          # 상세 페이지 컴포넌트
│   └── ScenarioTimeline.php        # 타임라인 컴포넌트
├── Forms/
│   ├── CreateScenarioForm.php      # 시나리오 생성 폼
│   ├── EditScenarioForm.php        # 시나리오 편집 폼
│   └── CreateStepForm.php          # 단계 생성 폼
└── Traits/
    ├── ScenarioProgressCalculator.php  # 진행률 계산
    └── ScenarioPermissionChecker.php   # 권한 체크
```

### 컨트롤러 구조
```php
// app/Http/Controllers/Api/ScenarioManager/
├── ScenarioGroupController.php
├── ScenarioController.php
├── SubScenarioController.php
├── ScenarioStepController.php
└── ScenarioCommentController.php
```

## 📈 확장 계획

### 단기 개선사항 (1-3개월)
- [ ] 드래그 앤 드롭 기능 강화
- [ ] 고급 필터링 및 검색
- [ ] 이메일 알림 시스템
- [ ] 모바일 반응형 개선
- [ ] 다크 모드 지원
- [ ] 기존 샌드박스 시스템과의 완전 통합

### 중기 확장 (3-6개월)
- [ ] 외부 시스템 연동 (Jira, GitHub, Slack 등)
- [ ] 고급 보고 및 분석 대시보드
- [ ] AI 기반 작업 분배 및 예측
- [ ] 템플릿 라이브러리
- [ ] 협업 기능 강화 (멘션, 태그 등)
- [ ] 실시간 협업 기능 (WebSocket)

### 장기 비전 (6개월+)
- [ ] 머신러닝 기반 진행 예측
- [ ] 자동화 워크플로우 빌더
- [ ] 크로스 플랫폼 앱 (React Native)
- [ ] 기업용 고급 기능 (SSO, 감사 로그 등)

---

## ✅ 구현 우선순위

### Phase 1: 핵심 기능 (2주)
1. ✅ 데이터베이스 스키마 설계
2. 🔄 기본 CRUD API 구현
3. 🔄 계층적 트리뷰 UI
4. 🔄 Jira 스타일 시나리오 상세 페이지

### Phase 2: 고급 기능 (2주)
1. 🔄 칸반보드 뷰
2. 🔄 실시간 협업 기능
3. 🔄 필터링 및 검색
4. 🔄 파일 첨부 기능

### Phase 3: 분석 및 보고 (1주)
1. 🔄 대시보드 메트릭스
2. 🔄 보고서 생성
3. 🔄 내보내기 기능

### Phase 4: 최적화 및 확장 (1주)
1. 🔄 성능 최적화 (N+1 쿼리 해결, 캐싱 적용)
2. 🔄 권한 시스템 고도화 (기존 PermissionService 연동)
3. 🔄 모바일 반응형 완성
4. 🔄 기존 샌드박스 네비게이션에 통합

## 🎯 최종 제안사항

### 1. 샌드박스 시스템 통합
- 현재 `/sandbox/scenario-manager` 라우트는 이미 존재하므로 이를 활용
- 기존 샌드박스 네비게이션 메뉴에 시나리오 관리자 추가
- GlobalFunctions 시스템과 유사한 아키텍처 적용

### 2. 단계별 구현 접근
- Phase 1에서 기본 CRUD와 트리뷰부터 시작
- 실제 사용하면서 피드백을 받고 개선
- Jira 티켓 스타일을 최대한 비슷하게 구현

### 3. 데이터 마이그레이션 고려
- 기존 샌드박스 데이터를 시나리오 그룹으로 변환하는 마이그레이션 스크립트 준비
- 점진적 마이그레이션으로 안정성 확보

---

## 🚀 다음 단계

이제 스펙이 완성되었습니다. 다음 프롬프트에서 다음 중 하나를 선택해주세요:

1. **"시나리오 관리 시스템 구현 시작"** - Phase 1부터 바로 시작
2. **"데이터베이스 마이그레이션부터 시작"** - DB 스키마 생성부터 시작
3. **"UI 프로토타입 먼저"** - 디자인과 UI 컴포넌트부터 시작
4. **"스펙 수정 요청: [구체적인 수정사항]"** - 특정 부분 수정 요청

어떤 방식으로 진행하시겠습니까?

💬 **이 스펙에 대한 의견이나 수정 제안이 있으시면 언제든 말씀해 주세요!**
