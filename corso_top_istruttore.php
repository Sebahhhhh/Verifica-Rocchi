<?php
require 'auth.php';
require_login();
require 'config.php';

// controlla solo i corsi con almeno 5 iscritti
// poi prendo i corsi associati agli istruttori e ordino per istruttore + iscritti desc

$risultati = $pdo->query("
    SELECT
        i.id_istruttore,
        i.nome AS inome,
        i.cognome AS icognome,
        c.nome_corso,
        cnt.num_iscritti
    FROM Istruttori i
    JOIN Corsi c ON c.id_istruttore = i.id_istruttore
    JOIN (
        SELECT id_corso, COUNT(*) AS num_iscritti
        FROM Iscrizioni_Corsi
        GROUP BY id_corso
        HAVING COUNT(*) >= 5
    ) cnt ON cnt.id_corso = c.id_corso
    ORDER BY i.cognome, cnt.num_iscritti DESC
")->fetchAll();

// prendo solo il corso con più iscritti per ogni istruttore
$top = [];
foreach ($risultati as $r) {
    $id = $r['id_istruttore'];
    if (!isset($top[$id])) {
        $top[$id] = $r;
    }
}
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

<h1>CORSO CON PIU ISCRITTI</h1>

<div class="box">
<?php if (empty($top)): ?>
    <p>nessuno</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Istruttore</th>
                <th>Corso</th>
                <th>N. Iscritti</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($top as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['icognome'] . ' ' . $r['inome']) ?></td>
                <td><?= htmlspecialchars($r['nome_corso']) ?></td>
                <td><?= $r['num_iscritti'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>

</body>
</html>
