<?php

namespace App\Model;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost';
    private $dbname = 'younifirst';
    private $username = 'root';
    private $password = '';
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            $db = new self();
            self::$instance = $db->connect();
        }
        return self::$instance;
    }

    private function connect()
    {
        try {
            $pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
}