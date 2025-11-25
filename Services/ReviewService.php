<?php
namespace app\services;

use app\repositories\ReviewRepository;
use app\models\Review;

class ReviewService
{
    private ReviewRepository $reviewRepository;
    
    public function __construct()
    {
        $connection = Database::getConnection();
        $this->reviewRepository = new ReviewRepository($connection);
    }
    
    public function createReview(string $fullName, string $message): array
    {
        $result = [
            'success' => false,
            'review' => null,
            'errors' => []
        ];
        
        $review = new Review($fullName, $message);
        
        if (!$review->isValid()) {
            $result['errors'] = $review->getValidationErrors();
            return $result;
        }
        
        try {
            $success = $this->reviewRepository->create($review);
            
            if ($success) {
                $result['success'] = true;
                $result['review'] = $review;
            } else {
                $result['errors'] = ['Ошибка при сохранении в базу данных'];
            }
        } catch (\PDOException $e) {
            $result['errors'] = ['Ошибка базы данных: ' . $e->getMessage()];
        }
        
        return $result;
    }
    
    public function getAllReviews(): array
    {
        return $this->reviewRepository->findAll();
    }
    
    public function getReviewById(int $id): ?Review
    {
        return $this->reviewRepository->findById($id);
    }
}
?>