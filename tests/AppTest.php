<?php

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    private static PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        try {
            self::$pdo = new PDO("mysql:host=db;dbname=ma_bdd;charset=utf8mb4", 'db_user', 'db_pwd');
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            self::markTestSkipped('Base de données inaccessible : ' . $e->getMessage());
        }
    }

    public function test_migrations_json_est_valide(): void
    {
        $data = json_decode(file_get_contents(__DIR__ . '/../migrations.json'), true);
        $this->assertNotNull($data, 'migrations.json contient du JSON invalide');
        $this->assertIsArray($data);
    }

    public function test_chaque_migration_a_un_nom_et_des_queries(): void
    {
        $data = json_decode(file_get_contents(__DIR__ . '/../migrations.json'), true);
        foreach ($data as $migration) {
            $this->assertArrayHasKey('name', $migration);
            $this->assertArrayHasKey('queries', $migration);
            $this->assertNotEmpty($migration['queries']);
        }
    }

    public function test_connexion_bdd(): void
    {
        $result = self::$pdo->query("SELECT 1")->fetchColumn();
        $this->assertEquals(1, $result);
    }

    public function test_table_db_table_existe(): void
    {
        $result = self::$pdo->query("SHOW TABLES LIKE 'db_table'")->fetch();
        $this->assertNotFalse($result, "La table db_table n'existe pas");
    }

    public function test_db_table_contient_des_donnees(): void
    {
        $count = self::$pdo->query("SELECT COUNT(*) FROM db_table")->fetchColumn();
        $this->assertGreaterThan(0, $count, 'La table db_table est vide');
    }

    public function test_les_lignes_ont_un_id_et_un_text(): void
    {
        $rows = self::$pdo->query("SELECT id, text FROM db_table")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $this->assertArrayHasKey('id', $row);
            $this->assertArrayHasKey('text', $row);
            $this->assertNotEmpty($row['text']);
        }
    }
}
