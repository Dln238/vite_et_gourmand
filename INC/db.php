<?php
// Configuration des variables d'environnement Docker
$host   = 'mysql';
$dbname = 'vite_et_gourmand';
$user   = 'admin';
$pass   = 'password';

try {
    // AJOUT CRITIQUE : charset=utf8mb4 et exécution de la commande SET NAMES UTF8
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4" // Force MySQL à parler en UTF-8 avec PHP
    ]);
} catch (PDOException $e) {
    die("Erreur critique de connexion à la base de données : " . $e->getMessage());
}
?>