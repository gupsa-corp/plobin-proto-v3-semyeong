<?php
/**
 * Form Publisher - 폼 미리보기 페이지
 */

// 백엔드 클래스 로드
require_once __DIR__ . '/../../Backend/Functions/FormPublisher/FormManager.php';
require_once __DIR__ . '/../../Backend/Functions/FormPublisher/FormRenderer.php';

$formManager = new FormManager();
$formId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$message = '';
$error = '';
$form = null;
$submissionResult = null;

// 폼 ID가 없으면 목록으로 리다이렉트
if (!$formId) {
    header('Location: /sandbox/form-publisher/list');
    exit;
}

// 폼 불러오기
$loadResult = $formManager->loadForm($formId);
if (!$loadResult['success']) {
    $error = $loadResult['error'];
} else {
    $form = $loadResult['form'];
}

// 폼 제출 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $form) {
    $submissionData = $_POST;
    
    // action 필드 제거
    unset($submissionData['action']);
    
    $submissionResult = $formManager->processFormSubmission($formId, $submissionData);
    
    if ($submissionResult['success']) {
        $message = $submissionResult['message'];
    } else {
        $error = isset($submissionResult['error']) ? $submissionResult['error'] : '폼 제출 중 오류가 발생했습니다.';
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Publisher - 폼 미리보기</title>
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
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-info {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .form-info h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .form-description {
            color: #555;
            line-height: 1.5;
        }
        
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s;
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
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background: #e0a800;
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
        
        /* 동적 폼 스타일 */
        .form-container h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .form-description {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        
        .dynamic-form .form-group {
            margin-bottom: 1.2rem;
        }
        
        .dynamic-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #555;
        }
        
        .required {
            color: #e74c3c;
        }
        
        .dynamic-form input,
        .dynamic-form textarea,
        .dynamic-form select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .dynamic-form input:focus,
        .dynamic-form textarea:focus,
        .dynamic-form select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .radio-option,
        .checkbox-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.5rem 0;
        }
        
        .radio-option input,
        .checkbox-option input {
            width: auto;
            margin: 0;
        }
        
        .radio-option label,
        .checkbox-option label {
            font-weight: normal;
            margin: 0;
        }
        
        .form-help {
            display: block;
            margin-top: 0.3rem;
            font-size: 0.85rem;
            color: #666;
            font-style: italic;
        }
        
        .dynamic-form .btn {
            margin-right: 0.5rem;
            margin-top: 1rem;
        }
        
        /* 제출 결과 스타일 */
        .submission-result {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 1.5rem;
        }
        
        .submission-result h3 {
            color: #28a745;
            margin-bottom: 1rem;
        }
        
        .result-data {
            margin-bottom: 1rem;
        }
        
        .result-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .result-item:last-child {
            border-bottom: none;
        }
        
        .result-item strong {
            color: #333;
            margin-right: 0.5rem;
        }
        
        .result-actions {
            text-align: center;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>👀 Form Publisher - 폼 미리보기</h1>
        <nav class="nav-links">
            <a href="/sandbox/form-publisher/editor">✏️ 새 폼 만들기</a>
            <a href="/sandbox/form-publisher/list">📋 폼 목록</a>
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

        <?php if ($form): ?>
            <div class="form-info">
                <h2><?= htmlspecialchars($form['title']) ?></h2>
                <div class="form-meta">
                    폼 ID: <?= $form['id'] ?> | 
                    생성일: <?= date('Y-m-d H:i:s', strtotime($form['created_at'])) ?> | 
                    수정일: <?= date('Y-m-d H:i:s', strtotime($form['updated_at'])) ?>
                </div>
                <?php if ($form['description']): ?>
                    <div class="form-description"><?= nl2br(htmlspecialchars($form['description'])) ?></div>
                <?php endif; ?>
            </div>

            <?php if ($submissionResult && $submissionResult['success']): ?>
                <!-- 제출 성공 결과 -->
                <?= FormRenderer::renderSubmissionResult($submissionResult['submission_data'], $submissionResult['form_structure']) ?>
            <?php else: ?>
                <!-- 폼 표시 -->
                <?php 
                if ($submissionResult && isset($submissionResult['errors'])) {
                    echo FormRenderer::renderErrors($submissionResult['errors']);
                }
                ?>
                
                <?= FormRenderer::renderForm($form['form_json'], $form['id']) ?>
            <?php endif; ?>

            <div class="actions">
                <a href="/sandbox/form-publisher/editor?edit=<?= $form['id'] ?>" class="btn btn-warning">✏️ 이 폼 편집</a>
                <a href="/sandbox/form-publisher/list" class="btn btn-secondary">📋 폼 목록</a>
                <?php if ($submissionResult && $submissionResult['success']): ?>
                    <a href="?id=<?= $formId ?>" class="btn btn-primary">🔄 다시 입력</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="form-container">
                <h2>❌ 폼을 찾을 수 없습니다</h2>
                <p>요청하신 폼을 찾을 수 없습니다. 폼이 삭제되었거나 존재하지 않는 ID입니다.</p>
                
                <div class="actions">
                    <a href="/sandbox/form-publisher/list" class="btn btn-primary">📋 폼 목록으로 이동</a>
                    <a href="/sandbox/form-publisher/editor" class="btn btn-secondary">✏️ 새 폼 만들기</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // 폼 제출 확인
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.dynamic-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.style.borderColor = '#e74c3c';
                        } else {
                            field.style.borderColor = '#ddd';
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        alert('필수 필드를 모두 입력해주세요.');
                    }
                });
            }
        });
    </script>
</body>
</html>