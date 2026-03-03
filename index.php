<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>R6.06 Maintenance applicative</title></head>
<body>
<header>
    <h1>R6.06 Maintenance applicative</h1><h2 style="color: crimson">Evaluation</h2>
    <p style="color: crimson">Modifiez ce projet à l'aide des outils vus ensemble pour améliorer la maintenabilité de ce projet et déployez le sur le serveur mis à votre disposition</p><p style="color: crimson">Vous êtes libre de modifier ce que vous souhaitez sur le projet, chaque amélioration (ou début d'amélioration) sera prise en compte dans la notation</p>
    <p style="color: crimson; font-weight: bold; border: solid 2px crimson; padding: 5px; width: fit-content;">Pensez à inviter cdiiv sur votre projet Github</p>
</header>

<?php
try {
    $p = new PDO("mysql:host=db;dbname=ma_bdd;charset=utf8mb4", 'db_user', 'db_pwd');

    $p->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $p->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur lors de la connexion à la BDD : ' . $e->getMessage();
    exit();
}

try {
    $d = $p->query("SELECT id,text FROM db_table")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $p->prepare('CREATE TABLE IF NOT EXISTS db_table (id INT PRIMARY KEY AUTO_INCREMENT, text VARCHAR(100) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4')->execute();
    $p->prepare('INSERT INTO db_table (text) VALUES (:text)')->execute([':text' => 'azerty']);
    $p->prepare('INSERT INTO db_table (text) VALUES (:text)')->execute([':text' => 'abcdef']);
    $p->prepare('INSERT INTO db_table (text) VALUES (:text)')->execute([':text' => 'xyz']);
    $p->prepare('INSERT INTO db_table (text) VALUES (:text)')->execute([':text' => '123456789']);
    $d = $p->query('SELECT id,text FROM db_table')->fetchAll(PDO::FETCH_ASSOC);
}
?>

<table>
    <thead style="font-weight: bold;"><tr><td style="border: solid black 1px">Id</td><td style="border: solid black 1px">Text</td></tr></thead>
    <tbody>
        <?php $i=0; while (true) { if(!key_exists($i, $d)) break;?>
            <tr><td style="border: solid black 1px"><?= $d[$i]['id'] ?></td><td style="border: solid black 1px"><?= $d[$i]['text'] ?></td></tr>
        <?php $i++; } ?>
    </tbody>
</table>
</body>
</html>
