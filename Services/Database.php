<?php
namespace app\services;

require_once 'EnvService.php';

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;
    
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            // Загружаем .env если еще не загружен
            if (empty(EnvService::getAll())) {
                EnvService::load();
            }
            
            $host = EnvService::get('PGHOST');
            $port = EnvService::get('PGPORT');
            $dbname = EnvService::get('PGDATABASE');
            $username = EnvService::get('PGUSERNAME');
            $password = EnvService::get('PGPASSWORD');
            
            try {
                $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
                self::$connection = new PDO($dsn, $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new PDOException("Database connection failed: " . $e->getMessage());
            }
        }
        
        return self::$connection;
    }
}
?>