<?php

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    private static PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        try {
            self::$pdo = new PDO(
                "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            );
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            self::markTestSkipped('Base de données inaccessible : ' . $e->getMessage());
        }
    }

    public function test_json(): void
    {
        $data = json_decode(file_get_contents(__DIR__ . '/../migrations.json'), true);
        $this->assertNotNull($data);
        $this->assertIsArray($data);
    }

    public function test_connexion_bdd(): void
    {
        $result = self::$pdo->query("SELECT 1")->fetchColumn();
        $this->assertEquals(1, $result);
    }

    public function test_table_existe(): void
    {
        $result = self::$pdo->query("SHOW TABLES LIKE 'db_table'")->fetch();
        $this->assertNotFalse($result);
    }

    public function test_table_contient_donnees(): void
    {
        $count = self::$pdo->query("SELECT COUNT(*) FROM db_table")->fetchColumn();
        $this->assertGreaterThan(0, $count);
    }
}
