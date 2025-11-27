<?php
$content = ob_start();
include __DIR__ . '/../layout.php';
?>

<div class="error-page">
    <div class="error-content">
        <div class="error-code">404</div>
        <h1 class="error-title">Страница не найдена</h1>
        <p class="error-message">
            К сожалению, запрашиваемая страница не существует или была перемещена.
        </p>
        
        <div class="error-actions">
            <a href="/" class="btn btn-primary">
                ← Вернуться на главную
            </a>
            <a href="/reviews" class="btn btn-secondary">
                Посмотреть отзывы
            </a>
        </div>
        
        <div class="error-help">
            <p>Если вы считаете, что это ошибка, пожалуйста, <a href="mailto:support@example.com">свяжитесь с нами</a>.</p>
        </div>
    </div>
</div>
