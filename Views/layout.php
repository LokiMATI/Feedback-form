<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система отзывов</title>
    <?php
        require "styles.php"
    ?>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="/">Главная</a>
            <a href="/reviews">Все отзывы</a>
        </div>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <strong>Ошибки:</strong>
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['form_data'])): ?>
            <?php $formData = $_SESSION['form_data']; unset($_SESSION['form_data']); ?>
        <?php endif; ?>
        
        <?php include $content; ?>
    </div>
</body>
</html>