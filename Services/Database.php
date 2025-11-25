<?php
namespace app\services;

use PDO;
use PDOException;

class Database
{
    private PDO $connection;
    
    public function __construct(
        string $host = 'localhost',
        int $port = 5432, // Порт PostgreSQL по умолчанию
        string $dbname = 'Feedback',
        string $username = 'Lucky',
        string $password = '58365836'
    ) {
        try {
            // DSN для PostgreSQL
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException("PostgreSQL connection failed: " . $e->getMessage());
        }
    }
    
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
?>