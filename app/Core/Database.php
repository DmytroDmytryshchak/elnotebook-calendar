<?php

// точка підключення до бази даних
// Патерн Singleton — гарантує що за весь час роботи додатку існує одне підключення PDO
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = require BASE_PATH . '/config/database.php';

        $dsn = 'mysql:host=' . $config['host']
            . ';dbname=' . $config['database']
            . ';charset=' . $config['charset'];

        try {
            $this->connection = new PDO($dsn, $config['username'], $config['password'], array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ));
        } catch (PDOException $e) {
            throw new RuntimeException('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    private function __clone() {}

    public function __wakeup()
    {
        throw new RuntimeException('Cannot unserialize singleton.');
    }
}