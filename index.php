<?php
require 'auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>VERIFICA ROCCHI</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>

<h1>GESTIONALE</h1>
<div class="box">
    <h2>Funzioni disponibili</h2>
    <ul>
        <li><a href="inserisci_iscrizione.php">Inserisci nuova iscrizione a un corso</a></li>
        <li> </li>
        <li><a href="corso_top_istruttore.php">Corso con più iscritti per istruttore (min 5)</a></li>
        <li> </li>
        <li><a href="iscritti_corso.php">Elenco iscritti a un corso + cambio corso</a></li>
        <li> </li>
        <li><a href="report.php">Report completo istruttori, corsi e iscritti</a></li>
        <li> </li>
        <li> </li>
        <li><a href="auth.php?logout=1">LOGOUT</a></li>
    </ul>
</div>

</body>
</html>
