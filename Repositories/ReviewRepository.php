<?php
namespace app\repositories;

use PDO;
use App\Models\Review;
use DateTime;

class ReviewRepository
{
    private PDO $connection;
    
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    
    /**
     * Получить все отзывы из базы данных
     * 
     * @return Review[]
     */
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
    
    /**
     * Найти отзыв по ID
     */
    public function findById(int $id): ?Review
    {
        $sql = "SELECT id, publication_time, full_name, message FROM reviews WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        
        return $this->mapToReview($row);
    }
    
    /**
     * Сохранить отзыв в базу данных
     */
    public function save(Review $review): bool
    {
        if ($review->id === 0) {
            return $this->insert($review);
        } else {
            return $this->update($review);
        }
    }
    
    private function insert(Review $review): bool
    {
        $sql = "INSERT INTO reviews (publication_time, full_name, message) 
                VALUES (:publication_time, :full_name, :message) 
                RETURNING id"; // PostgreSQL возвращает ID через RETURNING
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'publication_time' => $review->publicationTime->format('Y-m-d H:i:s'),
            'full_name' => $review->fullName,
            'message' => $review->message
        ]);
        
        // Получаем ID из результата
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['id'])) {
            $review->id = (int)$result['id'];
            return true;
        }
        
        return false;
    }
    
    private function update(Review $review): bool
    {
        $sql = "UPDATE reviews SET 
                publication_time = :publication_time,
                full_name = :full_name,
                message = :message
                WHERE id = :id";
        
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            'id' => $review->id,
            'publication_time' => $review->publicationTime->format('Y-m-d H:i:s'),
            'full_name' => $review->fullName,
            'message' => $review->message
        ]);
    }
    
    /**
     * Преобразовать массив данных из БД в объект Review
     */
    private function mapToReview(array $row): Review
    {
        $review = new Review();
        $review->id = (int)$row['id'];
        
        // Для PostgreSQL дата может быть в формате timestamp
        $publicationTime = $row['publication_time'];
        if (is_string($publicationTime)) {
            $review->publicationTime = DateTime::createFromFormat('Y-m-d H:i:s', $publicationTime);
        } else {
            $review->publicationTime = new DateTime($publicationTime);
        }
        
        $review->FullName = $row['full_name'];
        $review->message = $row['message'];
        
        return $review;
    }
}
?>