<?php
$content = ob_start();
include __DIR__ . '/../layout.php';
?>
<div class="card">
    <h1>Добавить отзыв</h1>
    <form method="POST" action="/reviews/store">
        <div class="form-group">
            <label for="full_name">Ваше имя:</label>
            <input type="text" id="full_name" name="full_name" 
                   value="<?= isset($formData) ? htmlspecialchars($formData['full_name']) : '' ?>" 
                   required maxlength="100">
        </div>

        <div class="form-group">
            <label for="email">Ваш Email:</label>
            <input type="text" id="email" name="email" 
                   value="<?= isset($formData) ? htmlspecialchars($formData['email']) : '' ?>" 
                   required maxlength="255">
        </div>
        
        <div class="form-group">
            <label for="message">Ваш отзыв:</label>
            <textarea id="message" name="message" required><?= isset($formData) ? htmlspecialchars($formData['message']) : '' ?></textarea>
        </div>
        
        <button type="submit" class="btn">Отправить отзыв</button>
    </form>
</div>

