<?php

$pdo = new PDO("mysql:host=db;dbname=ma_bdd;charset=utf8mb4", 'db_user', 'db_pwd');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
    id     INT PRIMARY KEY AUTO_INCREMENT,
    name   VARCHAR(255) NOT NULL UNIQUE,
    run_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$migrations = json_decode(file_get_contents(__DIR__ . '/migrations.json'), true);

foreach ($migrations as $migration) {
    $name = $migration['name'];
    $stmt = $pdo->prepare("SELECT id FROM migrations WHERE name = ?");
    $stmt->execute([$name]);

    if ($stmt->fetch()) {
        echo "Skipping $name\n";
        continue;
    }

    foreach ($migration['queries'] as $query) {
        $pdo->exec($query);
    }
    $pdo->prepare("INSERT INTO migrations (name) VALUES (?)")->execute([$name]);
    echo "Applied $name\n";
}

echo "Done.\n";
