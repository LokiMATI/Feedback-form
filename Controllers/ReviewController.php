<?php
namespace app\controllers;

use app\services\ReviewService;

class ReviewController
{
    private ReviewService $reviewService;
    
    public function __construct()
    {
        $this->reviewService = new ReviewService();
    }
    
    /**
     * Показать форму создания отзыва
     */
    public function create(): void
    {
        $reviews = $this->reviewService->getAllReviews();
        require __DIR__ . '/../Views/review/create.php';
    }
    
    /**
     * Обработать отправку формы
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }
        
        $fullName = trim($_POST['full_name'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        $result = $this->reviewService->createReview($fullName, $message);
        
        if ($result['success']) {
            $_SESSION['success_message'] = 'Отзыв успешно добавлен!';
        } else {
            $_SESSION['form_data'] = [
                'full_name' => $fullName,
                'message' => $message
            ];
            $_SESSION['errors'] = $result['errors'];
        }
        
        header('Location: /');
        exit;
    }
    
    /**
     * Показать список всех отзывов
     */
    public function index(): void
    {
        $reviews = $this->reviewService->getAllReviews();
        require __DIR__ . '/../Views/review/list.php';
    }
}
?>