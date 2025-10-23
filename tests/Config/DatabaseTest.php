<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Config;

use PHPUnit\Framework\TestCase;
use App\Config\Database;
use PDO;

class DatabaseTest extends TestCase
{
    public function testGetConnection()
    {
        $connection = Database::getConnection();

        // Pastikan koneksi berhasil
        self::assertNotNull($connection);
        self::assertInstanceOf(PDO::class, $connection);

        // Tes query sederhana pada tabel user (pastikan tabelnya memang ada)
        $stmt = $connection->query("SHOW TABLES LIKE 'users'");
        $result = $stmt->fetchAll();

        self::assertNotEmpty($result, "Tabel 'users' tidak ditemukan di database.");
    }

    public function testGetConnectionSingleton()
    {
        $connection1 = Database::getConnection();
        $connection2 = Database::getConnection();

        // Pastikan koneksi yang sama (singleton)
        self::assertSame($connection1, $connection2);
    }
}