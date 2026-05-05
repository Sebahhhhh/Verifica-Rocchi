<?php
require 'auth.php';
require_login();
require 'config.php';

// tutti gli istruttori con i loro corsi
$dati = $pdo->query("
    SELECT i.nome, i.cognome, c.nome_corso, c.livello_difficolta, c.durata_minuti
    FROM Istruttori i
    LEFT JOIN Corsi c ON c.id_istruttore = i.id_istruttore
    ORDER BY i.cognome, i.nome, c.nome_corso
")->fetchAll();

// ordinamento in ordine alfabetico per cognome e nome dell'istruttore, poi per nome del corso
// ...
/ ....


//
// NON HO AVUTO TEMPO DI TERMINARLO
//

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>VERIFICA ROCCHI</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>

<div><a href="index.php" class="link-home">Home</a></div>

<h1>CORSI</h1>

<div class="box">
    <table>
        <thead>
            <tr>
                <th>Istruttore</th>
                <th>Corso</th>
                <th>Livello</th>
                <th>Durata</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($dati as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['cognome'] . ' ' . $row['nome']) ?></td>
                <td><?= $row['nome_corso'] ? htmlspecialchars($row['nome_corso']) : '-' ?></td>
                <td><?= $row['livello_difficolta'] ?? '-' ?></td>
                <td><?= $row['durata_minuti'] ?? '-' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
