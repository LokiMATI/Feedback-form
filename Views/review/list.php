<?php
$content = ob_start();
include __DIR__ . '/../layout.php';
?>
<div class="card">
    <div class="header-with-refresh">
        <h1>Все отзывы (<span id="reviews-count"><?= count($reviews) ?></span>)</h1>
        <button id="refresh-btn" class="btn btn-secondary">
            <span class="btn-text">🔄 Обновить</span>
            <span class="btn-loading" style="display: none;">
                <span class="spinner"></span> Загрузка...
            </span>
        </button>
    </div>
    
    <div id="reviews-container">
        <?php if (empty($reviews)): ?>
            <p>Отзывов пока нет.</p>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <div class="review-author"><?= htmlspecialchars($review->fullName) ?></div>
                    <div class="review-email"><?= htmlspecialchars($review->email) ?></div>
                    <div class="review-date"><?= $review->publicationTime->format('Y-m-d H:i') ?></div>
                    <div class="review-message"><?= nl2br(htmlspecialchars($review->message)) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <p style="text-align: center; margin-top: 20px;">
        <a href="/" class="btn">Добавить отзыв</a>
    </p>
</div>

<script>
class ReviewsRefresher {
    constructor() {
        this.refreshBtn = document.getElementById('refresh-btn');
        this.reviewsContainer = document.getElementById('reviews-container');
        this.reviewsCount = document.getElementById('reviews-count');
        this.apiUrl = '/api/reviews';
        
        this.init();
    }
    
    init() {
        this.refreshBtn.addEventListener('click', () => this.refreshReviews());
    }
    
    async refreshReviews() {
        this.setLoading(true);
        
        try {
            const response = await fetch(this.apiUrl);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            this.displayReviews(result);
            
        } catch (error) {
            console.error('Error refreshing reviews:', error);
        } finally {
            this.setLoading(false);
        }
    }
    
    displayReviews(reviews) {
        if (reviews.length === 0) {
            this.reviewsContainer.innerHTML = '<p>Отзывов пока нет.</p>';
            this.reviewsCount.textContent = '0';
            return;
        }

        const reviewsHtml = reviews.map(review => `
            <div class="review-item">
                <div class="review-author">${this.escapeHtml(review.fullName)}</div>
                <div class="review-email">${this.escapeHtml(review.email)}</div>
                <div class="review-date">${this.escapeHtml(review.publicationTime.date.substr(0, 16))}</div>
                <div class="review-message">${this.escapeHtml(review.message).replace(/\n/g, '<br>')}</div>
            </div>
        `).join('');
        
        
        this.reviewsContainer.innerHTML = reviewsHtml;
        this.reviewsCount.textContent = reviews.length;
    }


    setLoading(loading) {
        const btnText = this.refreshBtn.querySelector('.btn-text');
        const btnLoading = this.refreshBtn.querySelector('.btn-loading');
        
        if (loading) {
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-block';
            this.refreshBtn.disabled = true;
        } else {
            btnText.style.display = 'inline-block';
            btnLoading.style.display = 'none';
            this.refreshBtn.disabled = false;
        }
    }
    
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    new ReviewsRefresher();
});
</script>