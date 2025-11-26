<?php
namespace App\Controllers;

require_once "../Services/ReviewService.php";

use app\services\ReviewService;

class ReviewController
{
    private ReviewService $reviewService;
    
    public function __construct()
    {
        $this->reviewService = new ReviewService();
    }
    
    public function create(): void
    {
        require __DIR__ . '/../Views/review/create.php';
    }
    
    /**
     * Обработать отправку формы через Fetch API
     */
    public function store(): void
    {
        echo "test";
        // Убедитесь, что это AJAX запрос и устанавливаем правильные заголовки
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'errors' => ['Method not allowed']], 405);
            return;
        }
        
        // Получаем данные из формы
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        $result = $this->reviewService->createReview($fullName, $email, $message);
        
        // Возвращаем JSON ответ
        if ($result['success']) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Отзыв успешно добавлен!',
                'review' => [
                    'id' => $result['review']->id,
                    'full_name' => $result['review']->FullName,
                    'email' => $result['review']->email,
                    'message' => $result['review']->message,
                    'publication_time' => $result['review']->getFormattedDate()
                ]
            ], 201);
        } else {
            $this->jsonResponse([
                'success' => false,
                'errors' => $result['errors']
            ], 422);
        }
    }
    
    /**
     * Вспомогательный метод для JSON ответов
     */
    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Получить все отзывы в JSON формате
     */
    public function apiIndex(): void
    {
        try {
            $reviews = $this->reviewService->getAllReviews();
            
            $reviewsData = [];
            foreach ($reviews as $review) {
                $reviewsData[] = [
                    'id' => $review->id,
                    'full_name' => $review->FullName,
                    'email' => $review->email,
                    'message' => $review->message,
                    'publication_time' => $review->getFormattedDate()
                ];
            }
            
            $this->jsonResponse([
                'success' => true,
                'data' => $reviewsData,
                'count' => count($reviewsData)
            ]);
            
        } catch (\Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'error' => 'Ошибка при получении отзывов'
            ], 500);
        }
    }
    
    public function index(): void
    {
        $reviews = $this->reviewService->getAllReviews();
        require __DIR__ . '/../Views/review/list.php';
    }
}
?>