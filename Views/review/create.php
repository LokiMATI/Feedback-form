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

<?php if (!empty($reviews)): ?>
<div class="card">
    <h2>Последние отзывы (<?= count($reviews) ?>)</h2>
    <?php foreach (array_slice($reviews, 0, 5) as $review): ?>
        <div class="review-item">
            <div class="review-author"><?= htmlspecialchars($review->FullName) ?></div>
            <div class="review-date"><?= $review->publicationTime->format('d.m.Y в H:i') ?></div>
            <div class="review-message"><?= nl2br(htmlspecialchars($review->message)) ?></div>
        </div>
    <?php endforeach; ?>
    
    <?php if (count($reviews) > 5): ?>
        <p style="text-align: center; margin-top: 15px;">
            <a href="/reviews">Показать все отзывы</a>
        </p>
    <?php endif; ?>
</div>
<?php endif; ?>
