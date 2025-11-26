<?php
$title = 'Добавить отзыв';
$content = ob_start();
include __DIR__ . '/../layout.php';
?>

<div class="card">
    <h1>Добавить отзыв</h1>
    
    <form id="review-form">
        <div class="form-group">
            <label for="full_name">Ваше имя: *</label>
            <input type="text" id="full_name" name="full_name" required maxlength="100">
            <div class="error-message" id="full_name_error"></div>
        </div>
        
        <div class="form-group">
            <label for="email">Email: *</label>
            <input type="email" id="email" name="email" required>
            <div class="error-message" id="email_error"></div>
        </div>
        
        <div class="form-group">
            <label for="message">Ваш отзыв: *</label>
            <textarea id="message" name="message" required></textarea>
            <div class="error-message" id="message_error"></div>
        </div>
        
        <button type="submit" class="btn" id="submit-btn">
            <span class="btn-text">Отправить отзыв</span>
            <span class="btn-loading" style="display: none;">
                <span class="spinner"></span> Отправка...
            </span>
        </button>
    </form>
    
    <div id="form-message"></div>
</div>

<script>
class ReviewForm {
    constructor() {
        this.form = document.getElementById('review-form');
        this.submitBtn = document.getElementById('submit-btn');
        this.formMessage = document.getElementById('form-message');
        
        if (!this.checkRequiredElements()) {
            console.error('Не найдены необходимые элементы DOM');
            return;
        }
        
        this.btnText = this.submitBtn.querySelector('.btn-text');
        this.btnLoading = this.submitBtn.querySelector('.btn-loading');
        
        this.init();
    }
    
    checkRequiredElements() {
        const requiredElements = [
            this.form,
            this.submitBtn,
            this.formMessage
        ];
        return requiredElements.every(element => element !== null);
    }
    
    init() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }
    
    async handleSubmit(e) {
        e.preventDefault();
        
        this.setLoading(true);
        this.clearErrors();
        this.hideMessage();
        
        // Используем FormData вместо JSON для простоты
        const formData = new FormData(this.form);
        
        try {
            const response = await fetch('/reviews/store', {
                method: 'POST',
                body: formData  // Отправляем как FormData, а не JSON
            });
            
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess('Отзыв успешно добавлен!');
                this.form.reset();
            } else {
                this.showErrors(result.errors);
            }
            
        } catch (error) {
            console.error('Error:', error);
            this.showError('Произошла ошибка при отправке. Попробуйте еще раз.');
        } finally {
            this.setLoading(false);
        }
    }
    
    showErrors(errors) {
        if (Array.isArray(errors)) {
            errors.forEach(error => {
                if (error.toLowerCase().includes('имя')) {
                    this.showFieldError('full_name', error);
                } else if (error.toLowerCase().includes('email')) {
                    this.showFieldError('email', error);
                } else if (error.toLowerCase().includes('сообщение')) {
                    this.showFieldError('message', error);
                } else {
                    this.showError(error);
                }
            });
        } else if (typeof errors === 'string') {
            this.showError(errors);
        }
    }
    
    showFieldError(fieldName, message) {
        const errorElement = document.getElementById(`${fieldName}_error`);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
        
        const input = this.form[fieldName];
        if (input) {
            input.classList.add('error');
        }
    }
    
    clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });
        
        document.querySelectorAll('input, textarea').forEach(el => {
            el.classList.remove('error');
        });
    }
    
    showSuccess(message) {
        this.showMessage(message, 'success');
    }
    
    showError(message) {
        this.showMessage(message, 'error');
    }
    
    showMessage(message, type) {
        if (!this.formMessage) return;
        
        this.formMessage.innerHTML = `
            <div class="alert alert-${type}">
                ${message}
            </div>
        `;
        this.formMessage.style.display = 'block';
        
        if (type === 'success') {
            setTimeout(() => {
                this.hideMessage();
            }, 5000);
        }
    }
    
    hideMessage() {
        if (this.formMessage) {
            this.formMessage.style.display = 'none';
        }
    }
    
    setLoading(loading) {
        if (loading) {
            this.btnText.style.display = 'none';
            this.btnLoading.style.display = 'inline-block';
            this.submitBtn.disabled = true;
        } else {
            this.btnText.style.display = 'inline-block';
            this.btnLoading.style.display = 'none';
            this.submitBtn.disabled = false;
        }
    }
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    new ReviewForm();
});
</script>
