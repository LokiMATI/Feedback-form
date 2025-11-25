<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once "Models/Review.php";
require_once "Services/Database.php";
require_once "Services/ReviewService.php";
require_once "Repositories/ReviewRepository.php";

use app\models\Review;
use app\services\Database;
use app\repositories\ReviewRepository;
use app\services\ReviewService;

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $database = new Database('localhost', 5432, 'Feedback', 'Lucky', '58365836');
    $connection = $database->getConnection();
    
    // Создаем репозиторий и сервис
    $reviewRepository = new ReviewRepository($connection);
    $reviewService = new ReviewService($reviewRepository);
    
    // Получаем все отзывы
    $reviews = $reviewService->getAllReviews();
    
    // Выводим результат
    echo "Найдено отзывов: " . count($reviews) . "\n\n";
    
    foreach ($reviews as $review) {
        echo "ID: " . $review->id . "\n";
        echo "Автор: " . $review->fullName . "\n";
        echo "Дата: " . $review->publicationTime->format('d.m.Y H:i') . "\n";
        echo "Сообщение: " . $review->message . "\n";
        echo "------------------------\n";
    }
    
} catch (PDOException $e) {
    echo "Ошибка PostgreSQL: ". $e->getMessage() ."\n";
} catch (Exception $e) {
    echo "Общая ошибка: " . $e->getMessage() . "\n";
}

?>