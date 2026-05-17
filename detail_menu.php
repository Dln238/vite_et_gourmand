<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: text/html; charset=utf-8');
require_once 'INC/db.php';
require_once 'CLASSES/Menu.php';

// Récupération sécurisée de l'ID passé dans l'URL
$menu_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Base de données Fail-Safe enrichie (Fiche produit premium)
$menus_fail_safe = [
    1 => [
        'titre' => 'Menu Festif',
        'prix' => 25.00,
        'presentation' => 'Une sélection d’exception spécialement cuisinée pour sublimer vos tables de fêtes et offrir une expérience culinaire inoubliable à vos convives.',
        'entree' => 'Foie gras de canard mi-cuit, toasts briochés et son chutney de figues maison',
        'plat' => 'Chapon rôti aux marrons forestiers et son écrasé de pommes de terre aux éclats de truffes',
        'dessert' => 'Bûche pâtissière traditionnelle au chocolat noir intense et zestes d’oranges confites',
        'theme' => 'Noel',
        'regime' => 'Classique',
        'allergenes' => 'Gluten, Fruits à coque'
    ],
    2 => [
        'titre' => 'Menu Vegetal',
        'prix' => 20.00,
        'presentation' => 'Une véritable escapade printanière saine et savoureuse, mettant à l’honneur la fraîcheur et la finesse des produits maraîchers de saison.',
        'entree' => 'Velouté de potimarron onctueux, éclats de châtaignes torréfiées et filet d’huile de noisette',
        'plat' => 'Risotto crémeux aux morilles sauvages, copeaux de parmesan affiné et jeunes pousses',
        'dessert' => 'Tarte fine aux pommes Granny Smith caramélisées, pointe de cannelle et glace vanille',
        'theme' => 'Printemps',
        'regime' => 'Vegetarien',
        'allergenes' => 'Gluten, Lactose, Fruits à coque'
    ]
];

// Sélection du menu correspondant à l'ID
$menu = isset($menus_fail_safe[$menu_id]) ? $menus_fail_safe[$menu_id] : $menus_fail_safe[1];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($menu['titre']) ?> - Détails</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --dark-color: #1a1d20;
            --primary-color: #FFC107;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
        }
        .navbar-custom { background-color: var(--dark-color); }
        .btn-custom { 
            background-color: var(--primary-color); 
            color: var(--dark-color); 
            font-weight: 600; 
            border: none;
            transition: all 0.3s ease;
        }
        .btn-custom:hover { 
            background-color: #e0a800; 
            transform: translateY(-1px);
        }
        .menu-image-placeholder {
    background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=1000') no-repeat center center/cover;
    min-height: 400px;
    border-radius: 20px;
}
        }
        .composition-box {
            background-color: #ffffff;
            border-radius: 16px;
            border-left: 5px solid var(--primary-color);
        }
        footer {
            background-color: var(--dark-color);
            color: #adb5bd;
        }
    </style>
</head>
<body>

    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom p-3 shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold text-warning fs-4" href="#">Vite & Gourmand</a>
                <div class="navbar-nav ms-auto align-items-center">
                    <a class="nav-link px-3" href="index.php">Accueil</a>
                    <a class="nav-link px-3" href="menus.php">Nos Menus</a>
                    <a class="nav-link px-3" href="contact.php">Contact</a>
                    <a class="btn btn-custom ms-2 px-4 shadow-sm" href="login.php">Panier (0)</a>
                </div>
            </div>
        </nav>

        <div class="container my-5 py-2">
            <div class="mb-4">
                <a href="menus.php" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">← Retour aux formules</a>
            </div>

            <div class="row g-5">
                <div class="col-md-5">
                    <div class="menu-image-placeholder shadow-sm">
                        <?= htmlspecialchars($menu['titre']) ?>
                    </div>
                </div>

                <div class="col-md-7">
                    <span class="text-muted text-uppercase fw-bold tracking-wider fs-7">Formule Traiteur</span>
                    <h1 class="display-5 fw-bold text-dark my-1"><?= htmlspecialchars($menu['titre']) ?></h1>
                    <h3 class="text-success fw-bold mb-4"><?= number_format($menu['prix'], 2, ',', ' ') ?> € <span class="fs-6 text-muted fw-normal">/ personne</span></h3>
                    
                    <p class="fs-5 text-muted mb-4">
                        <?= htmlspecialchars($menu['presentation']) ?>
                    </p>

                    <div class="composition-box shadow-sm p-4 mb-4">
                        <h4 class="fw-bold text-dark mb-3">✨ Composition du menu</h4>
                        
                        <div class="mb-3">
                            <span class="badge bg-dark text-warning mb-1">Entrée</span>
                            <p class="text-secondary mb-0 ps-1"><?= htmlspecialchars($menu['entree']) ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <span class="badge bg-dark text-warning mb-1">Plat Principal</span>
                            <p class="text-secondary mb-0 ps-1"><?= htmlspecialchars($menu['plat']) ?></p>
                        </div>
                        
                        <div>
                            <span class="badge bg-dark text-warning mb-1">Dessert</span>
                            <p class="text-secondary mb-0 ps-1"><?= htmlspecialchars($menu['dessert']) ?></p>
                        </div>
                    </div>

                    <div class="bg-white rounded-4 p-3 shadow-sm border mb-4">
                        <div class="row text-center g-2">
                            <div class="col-4 border-end">
                                <span class="text-muted d-block small">Thème</span>
                                <span class="badge bg-secondary mt-1 px-3"><?= htmlspecialchars($menu['theme']) ?></span>
                            </div>
                            <div class="col-4 border-end">
                                <span class="text-muted d-block small">Régime</span>
                                <span class="badge bg-info text-dark mt-1 px-3"><?= htmlspecialchars($menu['regime']) ?></span>
                            </div>
                            <div class="col-4">
                                <span class="text-muted d-block small">Commande min.</span>
                                <span class="fw-bold text-dark d-block mt-1">5 pers.</span>
                            </div>
                        </div>
                    </div>

                    <p class="small text-muted mb-4">⚠️ <strong>Allergènes possibles :</strong> <span class="text-danger"><?= htmlspecialchars($menu['allergenes']) ?></span></p>

                    <div class="alert alert-warning border-0 shadow-sm rounded-3 p-3" role="alert">
                        🔑 Vous devez être connecté pour réserver et planifier ce menu.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 mt-auto border-top border-secondary">
        <div class="container">
            <p class="mb-1 text-white fw-semibold">© 2026 Vite & Gourmand. Tous droits réservés.</p>
            <p class="mb-2 small text-warning fw-semibold">Horaires : Lundi - Samedi : 9h - 20h | Dimanche : Fermé</p>
            <p class="mb-0 small">Traiteur Événementiel Professionnel | Environnement Certifié Docker & POO</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>