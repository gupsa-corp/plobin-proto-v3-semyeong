-- Form Publisher 테이블 생성
-- 샌드박스용 폼 데이터 저장

CREATE TABLE IF NOT EXISTS sandbox_forms (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    form_json TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 인덱스 추가
CREATE INDEX IF NOT EXISTS idx_sandbox_forms_title ON sandbox_forms(title);
CREATE INDEX IF NOT EXISTS idx_sandbox_forms_created_at ON sandbox_forms(created_at);