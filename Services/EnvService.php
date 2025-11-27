<?php
namespace app\services;

class EnvService
{
    private static array $env = [];
    
    public static function load(?string $path = null): void
    {
        if ($path === null) {
            $path = '../.env';
        }
        if (!file_exists($path)) {
            throw new \Exception('.env file not found: ' . $path);
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Пропускаем комментарии
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Разбираем строки вида KEY=VALUE
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Убираем кавычки если есть
                $value = trim($value, '"\'');
                
                self::$env[$key] = $value;
            }
        }
    }
    
    public static function get(string $key)
    {
        return self::$env[$key];
    }
    
    public static function getAll(): array
    {
        return self::$env;
    }
}
?>