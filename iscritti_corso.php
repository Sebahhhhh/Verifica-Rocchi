<?php
require 'auth.php';
require_login();
require 'config.php';

// carica tutti i corsi
$tutti_corsi = $pdo->query("SELECT id_corso, nome_corso FROM Corsi ORDER BY nome_corso")->fetchAll();

// corso selezionato
$id_corso_sel = intval($_GET['id_corso'] ?? 0);
$iscritti = [];

// se è stato selezionato un corso carica gli iscritti
if ($id_corso_sel) {
    $stmt = $pdo->prepare("
        SELECT ic.id_iscrizione, m.nome, m.cognome, ic.orario_preferito
        FROM Iscrizioni_Corsi ic
        JOIN Membri m ON ic.id_membro = m.id_membro
        WHERE ic.id_corso = ?
        ORDER BY m.cognome, m.nome
    ");
    // esegue qurty
    $stmt->execute([$id_corso_sel]);
    $iscritti = $stmt->fetchAll();
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

<h1>ISCRITTI</h1>

<div class="box">
    <form method="GET" action="iscritti_corso.php">
        <label for="id_corso"></label>
        <select id="id_corso" name="id_corso">
            <option value=""></option>
            <?php foreach ($tutti_corsi as $c): ?>
                <option value="<?= $c['id_corso'] ?>" <?= ($c['id_corso'] == $id_corso_sel) ? 'selected' : '' ?>>
                    <?= $c['nome_corso'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="OK">
    </form>
</div>

<?php if ($id_corso_sel): ?>
<div class="box">
    <?php if (empty($iscritti)): ?>
        <p>Nessun iscritto.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>Nome</th><th>Orario</th><th>Azione</th></tr>
            </thead>
            <tbody>
            <?php foreach ($iscritti as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['cognome'] . ' ' . $m['nome']) ?></td>
                    <td><?= $m['orario_preferito'] ?? '-' ?></td>
                    <td>
                        <form method="POST" action="cambia_corso.php">
                            <input type="hidden" name="id_iscrizione" value="<?= $m['id_iscrizione'] ?>">
                            <input type="hidden" name="id_corso_corrente" value="<?= $id_corso_sel ?>">
                            <labl> Cambia Corso </labl>
                            <select name="id_corso_nuovo" required>
                                <option value=""></option>
                                <?php foreach ($tutti_corsi as $c): ?>
                                    <?php if ($c['id_corso'] != $id_corso_sel): ?>
                                        <option value="<?= $c['id_corso'] ?>"><?= $c['nome_corso'] ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn-cambia">OK</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php endif; ?>

</body>
</html>
