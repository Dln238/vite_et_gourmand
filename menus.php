<?php
header('Content-Type: text/html; charset=utf-8');

require_once 'INC/db.php';
require_once 'CLASSES/Menu.php';

$liste_menus = [];

try {
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
    // Fail-Safe
}

$liste_menus = [
    new Menu(1, 'Menu Festif', 'Entree: Foie gras | Plat: Chapon roti | Dessert: Buche', 25.00, 'Noel', 'Classique'),
    new Menu(2, 'Menu Vegetal', 'Entree: Veloute | Plat: Risotto | Dessert: Tarte', 20.00, 'Printemps', 'Vegetarien')
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Menus - Vite & Gourmand</title>
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
        .hero-section-small {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=1920') no-repeat center center/cover;
            color: white;
            padding: 60px 0;
            margin-bottom: 50px;
        }
        .card-menu {
            border: none;
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            background: #ffffff;
        }
        .card-menu:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        }
        .badge-price {
            background-color: #198754;
            font-size: 1.1rem;
            padding: 8px 16px;
            border-radius: 8px;
        }
        .badge-tag {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
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
                    <a class="nav-link px-3 active" href="menus.php">Nos Menus</a>
                    <a class="nav-link px-3" href="contact.php">Contact</a>
                    <a class="btn btn-custom ms-2 px-4 shadow-sm" href="login.php">Panier (<span id="cart-count">0</span>)</a>
                </div>
            </div>
        </nav>

        <div class="hero-section-small text-center shadow-sm">
            <div class="container py-2">
                <h1 class="fw-bold mb-2">Notre Carte & Formules</h1>
                <p class="lead fs-5 text-light-50 mb-0">Explorez nos propositions gastronomiques adaptées à toutes vos envies.</p>
            </div>
        </div>

        <div class="container">
            <div id="notif-zone" class="container my-3" style="max-width: 500px;"></div>

            <div class="row pb-5">
                <?php foreach ($liste_menus as $menu): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card card-menu shadow-sm h-100 p-2">
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h4 class="card-title fw-bold text-dark mb-3"><?= htmlspecialchars($menu->getTitre()) ?></h4>
                                    <p class="card-text text-muted mb-4 fs-6"><?= htmlspecialchars($menu->getDescription()) ?></p>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-4 flex-wrap">
                                        <span class="badge badge-price text-white m-0"><?= number_format($menu->getPrix(), 2, ',', ' ') ?> €</span>
                                        <span class="badge bg-secondary badge-tag"><?= htmlspecialchars($menu->getTheme()) ?></span>
                                        <span class="badge bg-info text-dark badge-tag"><?= htmlspecialchars($menu->getRegime()) ?></span>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <a href="detail_menu.php?id=<?= $menu->getId() ?>" class="btn btn-outline-secondary w-50 fw-semibold rounded-3 py-2">Détails</a>
                                        <button class="btn btn-dark w-50 fw-semibold rounded-3 py-2 btn-add-cart" data-id="<?= $menu->getId() ?>" data-name="<?= htmlspecialchars($menu->getTitre()) ?>">
                                            Ajouter au panier
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 mt-auto border-top border-secondary">
        <div class="container">
            <p class="mb-1 text-white fw-semibold">&copy; 2026 Vite & Gourmand. Tous droits réservés.</p>
            <p class="mb-2 small text-warning fw-semibold">Horaires : Lundi - Samedi : 9h - 20h | Dimanche : Fermé</p>
            <p class="mb-0 small">Traiteur Événementiel Professionnel | Environnement Certifié Docker & POO</p>
        </div>
    </footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Charger le panier enregistré au démarrage de la page
        let count = parseInt(localStorage.getItem('panier_count')) || 0;
        const cartCount = document.getElementById('cart-count');
        const notifZone = document.getElementById('notif-zone');
        
        if (cartCount) {
            cartCount.textContent = count;
        }

        document.querySelectorAll('.btn-add-cart').forEach(button => {
            button.addEventListener('click', (e) => {
                const menuName = e.target.getAttribute('data-name');
                
                // 2. Incrémenter et sauvegarder dans le navigateur
                count++;
                localStorage.setItem('panier_count', count);
                if (cartCount) {
                    cartCount.textContent = count;
                }

                // Notification dynamique injectée au bon endroit
                if (notifZone) {
                    notifZone.innerHTML = `
                        <div class="alert alert-success text-center alert-dismissible fade show shadow-sm rounded-3" role="alert">
                            <strong>${menuName}</strong> ajouté au panier !
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    
                    // Auto-suppression de l'alerte après 3 secondes
                    setTimeout(() => {
                        notifZone.innerHTML = '';
                    }, 3000);
                }
            });
        });
    });
    </script>
</body>
</html>