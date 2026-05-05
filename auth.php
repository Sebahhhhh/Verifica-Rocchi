<?php
session_start();

// se la persona è loggata bene altrimenti la rimanda alla pagina con il login.
// praticamente index la puoi accedere solo successivamente che hai passato il login sulla pagina login

function require_login() {
    if (empty($_SESSION['logged_in'])) {
        header('Location: login.php');
        exit;
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
