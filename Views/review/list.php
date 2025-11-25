<?php
$content = ob_start();
?>
<div class="card">
    <h1>Все отзывы (<?= count($reviews) ?>)</h1>
    
    <?php if (empty($reviews)): ?>
        <p>Отзывов пока нет.</p>
    <?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-item">
                <div class="review-author"><?= htmlspecialchars($review->FullName) ?></div>
                <div class="review-date"><?= $review->publicationTime->format('d.m.Y в H:i') ?></div>
                <div class="review-message"><?= nl2br(htmlspecialchars($review->message)) ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <p style="text-align: center; margin-top: 20px;">
        <a href="/" class="btn">Добавить отзыв</a>
    </p>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>