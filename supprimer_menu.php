<?php
session_start();
require_once 'inc/db.php';

// 1. SÉCURITÉ : Est-ce qu'on est admin ?
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. VÉRIFICATION : A-t-on bien reçu un ID ?
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    $id = $_GET['id'];

    // 3. SUPPRESSION SQL
    $stmt = $pdo->prepare("DELETE FROM menu WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

// 4. RETOUR MAISON : Quoi qu'il arrive, on retourne au tableau de bord
header("Location: admin.php");
exit;
?>