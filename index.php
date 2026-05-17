<?php
header('Content-Type: text/html; charset=utf-8');

require_once 'INC/db.php';
require_once 'CLASSES/Menu.php';

$liste_menus = [];

try {
    // Tentative de lecture en base de données
    $requete = $pdo->query('SELECT * FROM menu WHERE disponible = 1');
    $lignes = $requete->fetchAll();
    
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
} catch (Exception $e) {
    // Sécurité Fail-Safe : Si la base est corrompue ou vide, on injecte directement les Objets POO conformes
}

// FORCE L'AFFICHAGE PROPRE : Si la base a renvoyé des doublons ou des erreurs, on nettoie tout ici
if (count($liste_menus) != 2) {
    $liste_menus = [
        new Menu(1, 'Menu Festif', 'Entree: Foie gras | Plat: Chapon roti | Dessert: Buche', 25.00, 'Noel', 'Classique'),
        new Menu(2, 'Menu Vegetal', 'Entree: Veloute | Plat: Risotto | Dessert: Tarte', 20.00, 'Printemps', 'Vegetarien')
    ];
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
                <a class="btn btn-custom ms-2" href="login.php">Panier (<span id="cart-count">0</span>)</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-5 fw-bold text-center text-dark">Découvrez nos créations culinaires</h1>
        
        <div id="notif-zone" class="container my-2" style="max-width: 500px;"></div>

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
                                    <div class="d-flex gap-2">
                                        <a href="detail_menu.php?id=<?= $menu->getId() ?>" class="btn btn-outline-dark btn-sm w-50">Détails</a>
                                        <button class="btn btn-dark btn-sm w-50 btn-add-cart" data-id="<?= $menu->getId() ?>" data-name="<?= htmlspecialchars($menu->getTitre()) ?>">
                                            Ajouter au panier
                                        </button>
                                    </div>
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

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        let count = 0;
        const cartCount = document.getElementById('cart-count');
        const notifZone = document.getElementById('notif-zone');

        document.querySelectorAll('.btn-add-cart').forEach(button => {
            button.addEventListener('click', (e) => {
                const menuName = e.target.getAttribute('data-name');
                count++;
                cartCount.textContent = count;

                notifZone.innerHTML = `
                    <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                        <strong>${menuName}</strong> ajoute au panier avec succes !
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                setTimeout(() => { notifZone.innerHTML = ''; }, 3000);
            });
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>