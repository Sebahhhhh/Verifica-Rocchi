<?php
session_start();
require 'config.php';

// se sei gia loggato allora ti porta nella index
if (!empty($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}

$errore = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeutente = trim($_POST['nomeutente'] ?? '');
    $password   = $_POST['password'] ?? '';

    if ($password === 'verifica') {
    //  cerca un istruttore con il nomeutente che è uguale al cognome suo
        $stmt = $pdo->prepare("SELECT id_istruttore, nome, cognome FROM Istruttori WHERE LOWER(cognome) = LOWER(?)");
        $stmt->execute([$nomeutente]);
        $istruttore = $stmt->fetch();

        if ($istruttore) {
            $_SESSION['logged_in']     = true;
            $_SESSION['id_istruttore'] = $istruttore['id_istruttore'];
            $_SESSION['nome_completo'] = $istruttore['nome'] . ' ' . $istruttore['cognome'];
            header('Location: index.php');
            exit;
        }
    }
    //errore
    $errore = 'LOGIN ERRATO';
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

<h1>LOGIN</h1>

<?php if ($errore): ?>
    <div class="msg-err"><?= htmlspecialchars($errore) ?></div>
<?php endif; ?>

<div class="box">
    <form method="POST" action="login.php">
        <label for="nomeutente">Nome utente (cognome)</label>
        <input type="text" id="nomeutente" name="nomeutente" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <div><a href="#" onclick="document.querySelector('form').submit(); return false;" class="link-submit">ACCEDI</a></div>
    </form>
</div>

</body>
</html>
