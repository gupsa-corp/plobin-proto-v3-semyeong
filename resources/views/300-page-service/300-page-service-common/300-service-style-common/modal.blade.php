{{-- Modal Styles --}}
<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 480px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px 24px 16px;
    border-bottom: 1px solid var(--sidebar-border);
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-secondary);
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-body {
    padding: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-primary);
}

.required {
    color: #e53e3e;
}

.form-group input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--sidebar-border);
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
}

.form-group input:focus {
    border-color: var(--primary-color);
}

.input-prefix {
    position: relative;
}

.prefix {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    font-size: 14px;
}

.input-prefix input {
    padding-left: 28px;
}

.form-help {
    margin-top: 4px;
    font-size: 12px;
    color: var(--text-secondary);
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 32px;
}

.btn-cancel, .btn-create {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-cancel {
    background: transparent;
    border: 1px solid var(--sidebar-border);
    color: var(--text-primary);
}

.btn-cancel:hover {
    background: var(--hover-bg);
}

.btn-create {
    background: var(--primary-color);
    border: 1px solid var(--primary-color);
    color: white;
}

.btn-create:hover {
    background: #0bb39a;
}

.btn-create:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>

