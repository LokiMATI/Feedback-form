<?php
namespace App\Controllers;

class ErrorController
{
    /**
     * Показать страницу 404
     */
    public static function notFound(): void
    {
        // Устанавливаем HTTP статус 404
        http_response_code(404);
        // Показываем представление 404
        require __DIR__ . '/../Views/errors/404.php';
    }
}
?>