<?php
namespace app\controllers;

require_once '../Services/ReviewService.php';

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
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        
        $result = $this->reviewService->createReview($fullName, $email, $message);
        
        if ($result['success']) {
            $_SESSION['success_message'] = 'Отзыв успешно добавлен!';
        } else {
            $_SESSION['form_data'] = [
                'full_name' => $fullName,
                'email'=> $email,
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

    public function getReviews(): void
    {
        $reviews = $this->reviewService->getAllReviews();
        echo json_encode($reviews);
    }
}
?>