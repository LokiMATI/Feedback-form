<?php
namespace app\services;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;
    
    public static function getConnection(
        string $host = 'localhost',
        int $port = 5432,
        string $database = 'Feedback',
        string $username = 'Lucky',
        string $password = '58365836',
        ): PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = "pgsql:host=$host;port=$port;dbname=$database";
                self::$connection = new PDO($dsn, $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException("Database connection failed: " . $e->getMessage());
            }
        }
        
        return self::$connection;
    }
}
?>