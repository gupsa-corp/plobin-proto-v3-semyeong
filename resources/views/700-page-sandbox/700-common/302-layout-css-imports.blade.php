{{-- Sandbox Common CSS --}}
<style>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.3s ease-out;
}

.sandbox-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.sandbox-card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.sandbox-button {
    background: #3b82f6;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s;
}

.sandbox-button:hover {
    background: #2563eb;
}

.sandbox-input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.25rem;
    margin-bottom: 1rem;
}

.sandbox-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

@media (max-width: 768px) {
    .sandbox-container {
        padding: 1rem;
    }
}
</style>