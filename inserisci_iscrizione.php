<?php
require 'auth.php';
require_login();
require 'config.php';

$messaggio = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // lettura valori inseriti
    $id_corso  = (int)($_POST['id_corso'] ?? 0);
    $id_membro = (int)($_POST['id_membro'] ?? 0);
    $data_iscr = trim($_POST['data_iscrizione'] ?? '');
    $orario    = trim($_POST['orario_preferito'] ?? '');

    // controlla se hai messo i dati o meno
    if (!$id_corso || !$id_membro || $data_iscr === '') {
        $messaggio = '<div class="msg-err">inserisci tutti i dati.</div>';
    } else {

        // controlla solo se è gia presente o meno
        $chk = $pdo->prepare("SELECT 1 FROM Iscrizioni_Corsi WHERE id_corso = ? AND id_membro = ?");
        $chk->execute([$id_corso, $id_membro]);

        if ($chk->fetch()) {
            $messaggio = '<div class="msg-err">Questo membro è già iscritto.</div>';
        } else {

            // Inserimento nel database
            $stmt = $pdo->prepare("
                INSERT INTO Iscrizioni_Corsi (id_corso, id_membro, data_iscrizione, orario_preferito)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$id_corso, $id_membro, $data_iscr, $orario ?: null]);
            $messaggio = '<div class="msg-ok">Iscrizione registrata</div>';
        }
    }
}

// varie query

// query per select corsi
$corsi = $pdo->query("
    SELECT c.id_corso, c.nome_corso, i.cognome, i.nome
    FROM Corsi c
    JOIN Istruttori i ON i.id_istruttore = c.id_istruttore
    ORDER BY c.nome_corso, i.cognome, i.nome
")->fetchAll();


// query per la select dei membri
$membri = $pdo->query("SELECT id_membro, nome, cognome FROM Membri ORDER BY cognome, nome")->fetchAll();


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

<h1>ISCRIZIONE AD UN CORSO</h1>

<?= $messaggio ?>

<div class="box">
    <form method="POST" action="inserisci_iscrizione.php">
        <label for="id_corso">Corso</label>
        <select id="id_corso" name="id_corso" required>
            <option value=""></option>
            <?php foreach ($corsi as $c): ?>
                <option value="<?= $c['id_corso'] ?>">
                    <?= $c['nome_corso'] . ' (' . $c['cognome'] . ' ' . $c['nome'] . ')' ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="id_membro">Membro</label>
        <select id="id_membro" name="id_membro" required>
            <option value=""></option>
            <?php foreach ($membri as $m): ?>
                <option value="<?= $m['id_membro'] ?>">
                    <?= $m['cognome'] . ' ' . $m['nome'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="data_iscrizione">Data iscrizione</label>
        <input type="date" id="data_iscrizione" name="data_iscrizione"  required>

        <label for="orario_preferito">Orario preferito</label>
        <input type="time" id="orario_preferito" name="orario_preferito">

        <input type="submit" value="REGISTRA ISCRIZIONE">
    </form>
</div>

</body>
</html>
