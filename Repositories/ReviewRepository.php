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
        $sql = "INSERT INTO reviews ( full_name, message, email) 
                VALUES ( :full_name, :message, :email) 
                RETURNING id";
        
        $stmt = $this->connection->prepare($sql);

        echo "Test";

        $stmt->execute([
            ':full_name' => trim($review->fullName),
            ':message' => trim($review->message),
            ':email' => trim($review->email)
        ]);

        echo "Test";
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['id'])) {
            $review->id = (int)$result['id'];
            return true;
        }
        
        return false;
    }
    
    public function findAll(): array
    {
        
        $sql = "SELECT id, publication_time, email, full_name, message FROM reviews ORDER BY publication_time DESC";
        
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
        $review = new Review(
            $row['full_name'],
            $row['email'],
            $row['message']
        );
    
        $review->publicationTime = DateTime::createFromFormat('Y-m-d G:i:s.u', $row['publication_time']);
        $review->id = $row['id'];
        
        return $review;
    }
}
?>