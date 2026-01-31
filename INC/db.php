<?php
// Paramètres de connexion à la base de données (XAMPP par défaut)
$host = 'localhost';
$dbname = 'vite_et_gourmand';
$username = 'root';
$password = ''; // Sur XAMPP, le mot de passe est vide par défaut

try {
    // Création de la connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // On configure PDO pour qu'il nous prévienne en cas d'erreur (Indispensable pour débugger)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    // Si la connexion échoue, on arrête tout et on affiche l'erreur
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}