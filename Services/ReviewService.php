<?php
namespace app\services;

use App\Repositories\ReviewRepository;
use App\Models\Review;

class ReviewService
{
    private ReviewRepository $reviewRepository;
    
    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }
    
    /**
     * Получить все отзывы
     * 
     * @return Review[]
     */
    public function getAllReviews(): array
    {
        return $this->reviewRepository->findAll();
    }
    
    /**
     * Создать новый отзыв
     */
    public function createReview(string $fullName, string $message): Review
    {
        $review = new Review();
        $review->FullName = $fullName;
        $review->message = $message;
        $review->publicationTime = new \DateTime();
        
        $this->reviewRepository->save($review);
        
        return $review;
    }
}
?>