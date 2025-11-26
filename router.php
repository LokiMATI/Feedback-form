<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'Controllers/ReviewController.php';

session_start();

// Простой роутер
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    'GET' => [
        '/' => 'ReviewController@create',
        '/reviews' => 'ReviewController@index',
    ],
    'POST' => [
        '/reviews/store' => 'ReviewController@store',
    ]
];

if (isset($routes[$method][$request])) {
    list($controller, $action) = explode('@', $routes[$method][$request]);
    $controllerClass = "app\\controllers\\$controller";
    
    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass();
        
        $controllerInstance->$action();
    } else {
        http_response_code(404);
        echo "Контроллер не найден";
    }
} else {
    http_response_code(404);
    echo "Страница не найдена";
}
?>