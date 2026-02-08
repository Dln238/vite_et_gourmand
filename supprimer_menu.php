<?php
session_start();
require_once 'inc/db.php';

// 1. SÉCURITÉ : Seul l'admin a le droit de supprimer !
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. VÉRIFICATION DE L'ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // 3. SUPPRESSION
    try {
        $stmt = $pdo->prepare("DELETE FROM menu WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        // Redirection vers l'admin après suppression
        header("Location: admin.php?message=deleted");
    } catch (Exception $e) {
        echo "Erreur lors de la suppression : " . $e->getMessage();
    }
} else {
    // Si pas d'ID, on renvoie vers l'admin
    header("Location: admin.php");
}
exit;