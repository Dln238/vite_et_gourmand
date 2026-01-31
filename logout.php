<?php
session_start(); // On récupère la session en cours
session_destroy(); // On la détruit (on jette la clé)
header("Location: index.php"); // On redirige vers l'accueil
exit;
?>