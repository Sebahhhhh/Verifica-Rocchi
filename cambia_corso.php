<?php
require 'auth.php';
require_login();
require 'config.php';

// post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: iscritti_corso.php');
    exit;
}


$id_iscrizione     = intval($_POST['id_iscrizione'] ?? 0);
$id_corso_nuovo    = intval($_POST['id_corso_nuovo'] ?? 0);
$id_corso_corrente = intval($_POST['id_corso_corrente'] ?? 0);

// fa la validazione
if (!$id_iscrizione || !$id_corso_nuovo) {
    header('Location: iscritti_corso.php?id_corso=' . $id_corso_corrente);
    exit;
}

// recupera l'id del mebrmo
$stmt = $pdo->prepare("SELECT id_membro FROM Iscrizioni_Corsi WHERE id_iscrizione = ?");
$stmt->execute([$id_iscrizione]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    header('Location: iscritti_corso.php?id_corso=' . $id_corso_corrente);
    exit;
}

// Aggiorna il corso
$stmt = $pdo->prepare("UPDATE Iscrizioni_Corsi SET id_corso = ? WHERE id_iscrizione = ?");
$stmt->execute([$id_corso_nuovo, $id_iscrizione]);

header('Location: iscritti_corso.php?id_corso=' . $id_corso_corrente);
exit;
?>
