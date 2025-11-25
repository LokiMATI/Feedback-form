<?php
namespace app\services;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;
    
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../../config/database.php';
            
            try {
                $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
                self::$connection = new PDO($dsn, $config['username'], $config['password']);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException("Database connection failed: " . $e->getMessage());
            }
        }
        
        return self::$connection;
    }
}
?>