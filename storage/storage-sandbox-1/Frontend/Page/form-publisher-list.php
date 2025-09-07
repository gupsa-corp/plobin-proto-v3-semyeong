<?php
/**
 * Form Publisher - 폼 목록 페이지
 */

// 백엔드 클래스 로드
require_once __DIR__ . '/../../Backend/Functions/FormPublisher/FormManager.php';

$formManager = new FormManager();
$message = '';
$error = '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// 액션 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $formId = isset($_POST['form_id']) ? (int)$_POST['form_id'] : null;
    
    switch ($action) {
        case 'delete':
            if ($formId) {
                $deleteResult = $formManager->deleteForm($formId);
                if ($deleteResult['success']) {
                    $message = $deleteResult['message'];
                } else {
                    $error = $deleteResult['error'];
                }
            }
            break;
            
        case 'duplicate':
            if ($formId) {
                $duplicateResult = $formManager->duplicateForm($formId);
                if ($duplicateResult['success']) {
                    $message = '폼이 성공적으로 복사되었습니다.';
                } else {
                    $error = $duplicateResult['error'];
                }
            }
            break;
    }
}

// 폼 목록 조회
$formsResult = $formManager->getAllForms($search, $limit, $offset);
$forms = $formsResult['success'] ? $formsResult['forms'] : [];
$total = $formsResult['success'] ? $formsResult['total'] : 0;
$totalPages = ceil($total / $limit);

// 통계 정보
$statsResult = $formManager->getStatistics();
$stats = $statsResult['success'] ? $statsResult['statistics'] : null;
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Publisher - 폼 목록</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 1.5rem;
        }
        
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.2);
            transition: background 0.3s;
        }
        
        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card h3 {
            color: #667eea;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-card p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .search-section {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .search-form {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .search-form input {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .search-form input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .forms-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .table-header {
            background: #667eea;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-header h2 {
            font-size: 1.2rem;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #555;
        }
        
        .table tr:hover {
            background: #f8f9fa;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s;
        }
        
        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a6fd8;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin: 2rem 0;
        }
        
        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 3px;
            text-decoration: none;
            color: #667eea;
            background: white;
        }
        
        .pagination a:hover {
            background: #667eea;
            color: white;
        }
        
        .pagination .current {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .empty-state h2 {
            color: #666;
            margin-bottom: 1rem;
        }
        
        .empty-state p {
            color: #999;
            margin-bottom: 2rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-description {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .form-title {
            font-weight: 600;
            color: #333;
        }
        
        .text-muted {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>📋 Form Publisher - 폼 목록</h1>
        <nav class="nav-links">
            <a href="/sandbox/form-publisher/editor">✏️ 새 폼 만들기</a>
            <a href="../index.php">🏠 홈</a>
        </nav>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- 통계 정보 -->
        <?php if ($stats): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?= $stats['total_forms'] ?></h3>
                    <p>총 폼 개수</p>
                </div>
                <div class="stat-card">
                    <h3><?= $stats['recent_forms'] ?></h3>
                    <p>최근 7일 생성</p>
                </div>
                <?php if ($stats['latest_form']): ?>
                    <div class="stat-card">
                        <h3><?= htmlspecialchars($stats['latest_form']['title']) ?></h3>
                        <p>최근 생성 폼<br><small><?= date('m/d H:i', strtotime($stats['latest_form']['created_at'])) ?></small></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- 검색 섹션 -->
        <div class="search-section">
            <form method="GET" class="search-form">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="폼 제목이나 설명으로 검색...">
                <button type="submit" class="btn btn-primary">🔍 검색</button>
                <?php if ($search): ?>
                    <a href="?" class="btn btn-secondary">❌ 초기화</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- 폼 목록 -->
        <?php if (!empty($forms)): ?>
            <div class="forms-table">
                <div class="table-header">
                    <h2>폼 목록 (총 <?= $total ?>개)</h2>
                    <a href="/sandbox/form-publisher/editor" class="btn btn-success">➕ 새 폼 만들기</a>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>제목</th>
                            <th>설명</th>
                            <th>생성일</th>
                            <th>수정일</th>
                            <th>액션</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($forms as $form): ?>
                            <tr>
                                <td class="text-muted">#<?= $form['id'] ?></td>
                                <td>
                                    <div class="form-title"><?= htmlspecialchars($form['title']) ?></div>
                                </td>
                                <td>
                                    <div class="form-description" title="<?= htmlspecialchars($form['description']) ?>">
                                        <?= $form['description'] ? htmlspecialchars($form['description']) : '<em>설명 없음</em>' ?>
                                    </div>
                                </td>
                                <td class="text-muted"><?= date('m/d H:i', strtotime($form['created_at'])) ?></td>
                                <td class="text-muted"><?= date('m/d H:i', strtotime($form['updated_at'])) ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="/sandbox/form-publisher/preview/<?= $form['id'] ?>" 
                                           class="btn btn-primary btn-sm" title="미리보기">👀</a>
                                        <a href="/sandbox/form-publisher/editor?edit=<?= $form['id'] ?>" 
                                           class="btn btn-warning btn-sm" title="편집">✏️</a>
                                        
                                        <form method="POST" style="display: inline;" 
                                              onsubmit="return confirm('이 폼을 복사하시겠습니까?')">
                                            <input type="hidden" name="action" value="duplicate">
                                            <input type="hidden" name="form_id" value="<?= $form['id'] ?>">
                                            <button type="submit" class="btn btn-secondary btn-sm" title="복사">📋</button>
                                        </form>
                                        
                                        <form method="POST" style="display: inline;" 
                                              onsubmit="return confirm('정말로 이 폼을 삭제하시겠습니까?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="form_id" value="<?= $form['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" title="삭제">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- 페이지네이션 -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">‹ 이전</a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="current"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">다음 ›</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- 빈 상태 -->
            <div class="empty-state">
                <?php if ($search): ?>
                    <h2>🔍 검색 결과가 없습니다</h2>
                    <p>'<?= htmlspecialchars($search) ?>'에 대한 검색 결과가 없습니다.</p>
                    <a href="?" class="btn btn-primary">전체 목록 보기</a>
                <?php else: ?>
                    <h2>📝 아직 생성된 폼이 없습니다</h2>
                    <p>첫 번째 폼을 만들어보세요!</p>
                    <a href="/sandbox/form-publisher/editor" class="btn btn-primary">➕ 새 폼 만들기</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // 테이블 행 클릭시 미리보기로 이동
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.table tbody tr');
            
            rows.forEach(row => {
                row.addEventListener('click', function(e) {
                    // 액션 버튼 클릭시는 무시
                    if (e.target.closest('.actions')) {
                        return;
                    }
                    
                    const firstAction = row.querySelector('.actions a');
                    if (firstAction) {
                        window.location.href = firstAction.href;
                    }
                });
                
                // 마우스 호버 효과
                row.style.cursor = 'pointer';
            });
        });
        
        // 검색 입력시 실시간 필터링 (선택적)
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length > 2 || this.value.length === 0) {
                        this.form.submit();
                    }
                }, 500);
            });
        }
    </script>
</body>
</html>