<?php
// Force l'affichage en UTF-8 pour régler définitivement le problème des accents bizarres
header('Content-Type: text/html; charset=utf-8');

// 1. Inclusion des composants d'accès aux données
require_once 'INC/db.php';
require_once 'CLASSES/Menu.php';

// 2. Exécution de la requête SQL pour récupérer les menus
try {
    $requete = $pdo->query('SELECT * FROM menu WHERE disponible = 1');
    $lignes = $requete->fetchAll();
    
    $liste_menus = [];
    
    // 3. Transformation des lignes SQL en Objets de la classe Menu
    foreach ($lignes as $ligne) {
        $liste_menus[] = new Menu(
            (int)$ligne['id'],
            $ligne['titre'],
            $ligne['description'],
            (float)$ligne['prix'],
            $ligne['theme'] ?? '',
            $ligne['regime'] ?? ''
        );
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand - Traiteur Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom { background-color: #212529; }
        .btn-custom { background-color: #FFC107; color: #212529; font-weight: bold; border: none; }
        .btn-custom:hover { background-color: #e0a800; color: #212529; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom p-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning" href="#">Vite & Gourmand</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="index.php">Accueil</a>
                <a class="nav-link" href="menus.php">Nos Menus</a>
                <a class="nav-link" href="contact.php">Contact</a>
                <a class="btn btn-custom ms-2" href="login.php">Connexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-5 fw-bold text-center text-dark">Découvrez nos créations culinaires</h1>
        
        <div class="row">
            <?php if (!empty($liste_menus)): ?>
                <?php foreach ($liste_menus as $menu): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100 border-0">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title fw-bold text-uppercase text-dark mb-3"><?= htmlspecialchars($menu->getTitre()) ?></h5>
                                    <p class="card-text text-muted mb-4"><?= htmlspecialchars($menu->getDescription()) ?></p>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-success fs-5 p-2"><?= number_format($menu->getPrix(), 2, ',', ' ') ?> €</span>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($menu->getTheme()) ?></span>
                                        <span class="badge bg-info text-dark"><?= htmlspecialchars($menu->getRegime()) ?></span>
                                    </div>
                                    <a href="detail_menu.php?id=<?= $menu->getId() ?>" class="btn btn-outline-dark btn-sm w-100 mt-2">Voir le détail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="alert alert-warning text-center">Aucun menu n'est disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>