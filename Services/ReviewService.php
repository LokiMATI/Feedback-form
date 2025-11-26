<?php
namespace app\services;

require_once '../Models/Review.php';
require_once '../Repositories/ReviewRepository.php';
require_once 'Database.php';

use app\repositories\ReviewRepository;
use app\models\Review;
use Exception;

class ReviewService
{
    private ReviewRepository $reviewRepository;
    
    public function __construct()
    {
        $connection = Database::getConnection();
        $this->reviewRepository = new ReviewRepository($connection);
    }
    
    public function createReview(string $fullName, string $email, string $message): array
    {
        $result = [
            'success' => false,
            'review' => null,
            'errors' => []
        ];

        $review = new Review($fullName, $email, $message);
        
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
        } catch (Exception $e) {
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