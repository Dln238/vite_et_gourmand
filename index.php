
<?php
// 1. Inclusion des composants d'accès aux données (Fichiers déjà créés)
require_once 'INC/db.php';
require_once 'CLASSES/Menu.php';

// 2. Exécution de la requête SQL pour récupérer les menus (Utilisation de $pdo de db.php)
try {
    $requete = $pdo->query('SELECT * FROM menu WHERE disponible = 1');
    $lignes = $requete->fetchAll();
    
    $liste_menus = [];
    
    // 3. Transformation des lignes SQL en Objets de la classe Menu (Logique POO)
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/all.min.css" rel="stylesheet">
    <style>
        .navbar-custom { background-color: #212529; }
        .btn-custom { background-color: #FFC107; color: #212529; font-weight: bold; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom p-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Vite & Gourmand</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="index.php">Accueil</a>
                <a class="nav-link" href="menus.php">Nos Menus</a>
                <a class="nav-link" href="contact.php">Contact</a>
                <a class="btn btn-custom ms-2" href="login.php">Connexion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4 fw-bold text-center">Découvrez nos créations culinaires</h1>
        
        <div class="row">
            <?php if (!empty($liste_menus)): ?>
                <?php foreach ($liste_menus as $menu): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-uppercase"><?= htmlspecialchars($menu->getTitre()) ?></h5>
                                <p class="card-text text-muted"><?= htmlspecialchars($menu->getDescription()) ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-success fs-6"><?= number_format($menu->getPrix(), 2, ',', ' ') ?> €</span>
                                    <span class="text-secondary small"><?= htmlspecialchars($menu->getTheme()) ?> | <?= htmlspecialchars($menu->getRegime()) ?></span>
                                </div>
                                <a href="detail_menu.php?id=<?= $menu->getId() ?>" class="btn btn-outline-dark btn-sm w-100 mt-3">Voir le détail</a>
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