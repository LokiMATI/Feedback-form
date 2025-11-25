<?php
namespace app\repositories;

use PDO;
use app\models\Review;
use DateTime;

class ReviewRepository
{
    private PDO $connection;
    
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    
    public function create(Review $review): bool
    {
        $sql = "INSERT INTO reviews (publication_time, full_name, message) 
                VALUES (:publication_time, :full_name, :message) 
                RETURNING id";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'publication_time' => $review->publicationTime->format('Y-m-d H:i:s'),
            'full_name' => trim($review->FullName),
            'message' => trim($review->message)
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['id'])) {
            $review->id = (int)$result['id'];
            return true;
        }
        
        return false;
    }
    
    public function findAll(): array
    {
        $sql = "SELECT id, publication_time, full_name, message FROM reviews ORDER BY publication_time DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        
        $reviews = [];
        while ($row = $stmt->fetch()) {
            $reviews[] = $this->mapToReview($row);
        }
        
        return $reviews;
    }
    
    public function findById(int $id): ?Review
    {
        $sql = "SELECT id, publication_time, full_name, message FROM reviews WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $row = $stmt->fetch();
        return $row ? $this->mapToReview($row) : null;
    }
    
    private function mapToReview(array $row): Review
    {
        $review = new Review();
        $review->id = (int)$row['id'];
        $review->publicationTime = DateTime::createFromFormat('Y-m-d H:i:s', $row['publication_time']);
        $review->FullName = $row['full_name'];
        $review->message = $row['message'];
        
        return $review;
    }
}
?>