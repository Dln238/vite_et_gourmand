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

// FORCE L'AFFICHAGE PROPRE (Pas de doublons, pas d'accents cassés)
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
    <title>Vite & Gourmand - Traiteur Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* ==========================================================================
           CHARTE GRAPHIQUE & VARIABLES
           ========================================================================== */
        :root {
            --dark-color: #1a1d20;
            --primary-color: #ffc107;
            --primary-hover: #e0a800;
            --bg-light: #f9f9f9;
        }

        html, body {
            height: 100%;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            background-color: var(--bg-light);
        }

        .main-content {
            flex: 1;
        }

        /* ==========================================================================
           ANIMATIONS
           ========================================================================== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-section h1, .hero-section p, .card-menu {
            animation: fadeInUp 0.6s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
        }

        /* ==========================================================================
           BARRE DE NAVIGATION (HEADER)
           ========================================================================== */
        .navbar-custom {
            background-color: var(--dark-color) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .navbar-nav .nav-link {
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-1px);
        }

        .btn-custom {
            background-color: var(--primary-color);
            color: var(--dark-color);
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--primary-hover);
            color: var(--dark-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3) !important;
        }

        /* ==========================================================================
           HERO BANNER (IMAGE D'ACCUEIL)
           ========================================================================== */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), 
                        url('https://images.unsplash.com/photo-1555244162-803834f70033?q=80&w=1920') no-repeat center center/cover;
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }

        /* ==========================================================================
           CARTES DES MENUS & INTERACTIONS
           ========================================================================== */
        .card-menu {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }

        .card-menu:hover {
            transform: translateY(-10px) scale(1.01) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
        }

        .badge-price {
            background-color: #198754;
            font-size: 1.1rem;
            border-radius: 8px;
            padding: 8px 14px;
        }

        .btn-dark, .btn-outline-secondary {
            font-weight: 600;
            transition: all 0.3s ease !important;
            border-radius: 8px;
        }

        .btn-dark:hover {
            background-color: #343a40 !important;
            transform: scale(1.03);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15) !important;
        }

        .btn-outline-secondary:hover {
            transform: scale(1.03);
        }

        /* ==========================================================================
           FOOTER
           ========================================================================== */
        footer {
            background-color: var(--dark-color) !important;
            color: #adb5bd;
            border-top: 1px solid #2c3034;
            margin-top: auto;
        }
    </style>
</head>
<body>

    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom p-3 shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold text-warning fs-4" href="#">Vite & Gourmand</a>
                <div class="navbar-nav ms-auto align-items-center">
                    <a class="nav-link px-3 active" href="index.php">Accueil</a>
                    <a class="nav-link px-3" href="menus.php">Nos Menus</a>
                    <a class="nav-link px-3" href="contact.php">Contact</a>
                    <a class="btn btn-custom ms-2 px-4 shadow-sm" href="login.php">Panier (<span id="cart-count">0</span>)</a>
                </div>
            </div>
        </nav>

        <div class="hero-section text-center shadow-sm">
            <div class="container py-4">
                <h1 class="display-4 fw-bold mb-3 text-white">Vite & Gourmand</h1>
                <p class="lead fs-4 text-light mb-0">Votre traiteur événementiel d'exception, sur mesure et clé en main.</p>
            </div>
        </div>

        <div class="container mb-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark pb-2">Découvrez nos créations culinaires</h2>
                <p class="text-muted">Des formules pensées pour sublimer vos événements</p>
            </div>
            
            <div id="notif-zone" class="container my-3" style="max-width: 500px;"></div>

            <div class="row">
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
                                        <span class="badge bg-secondary"><?= htmlspecialchars($menu->getTheme()) ?></span>
                                        <span class="badge bg-info text-dark"><?= htmlspecialchars($menu->getRegime()) ?></span>
                                    </div>
                                    <div class="d-flex gap-3">
                                        <a href="detail_menu.php?id=<?= $menu->getId() ?>" class="btn btn-outline-secondary w-50 fw-semibold py-2">Détails</a>
                                        <button class="btn btn-dark w-50 fw-semibold py-2 btn-add-cart" data-id="<?= $menu->getId() ?>" data-name="<?= htmlspecialchars($menu->getTitre()) ?>">
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

        <div class="bg-white py-5 border-top border-bottom my-5 shadow-sm">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark pb-1">Ce que disent nos clients</h2>
                    <p class="text-muted">Note globale : <span class="text-warning fw-bold">4.9/5 ★★★★★</span> sur Google Reviews</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card card-menu p-4 h-100 border shadow-sm">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning text-dark fw-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                    TM
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-bold mb-0 text-dark">Thomas Martin</h6>
                                    <small class="text-warning">★★★★★</small>
                                </div>
                                <span class="ms-auto text-muted small">Il y a 1 sem.</span>
                            </div>
                            <p class="text-secondary card-text fs-6 mb-0">
                                "Une prestation incroyable pour notre mariage ! Le Menu Festif a bluffé tous nos invités. Le chapon était d'une tendresse rare. Je recommande les yeux fermés."
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-menu p-4 h-100 border shadow-sm">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success text-white fw-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                    SL
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-bold mb-0 text-dark">Sarah Leroy</h6>
                                    <small class="text-warning">★★★★★</small>
                                </div>
                                <span class="ms-auto text-muted small">Il y a 2 sem.</span>
                            </div>
                            <p class="text-secondary card-text fs-6 mb-0">
                                "Commande passée pour un repas d'entreprise avec la formule Végétale. Le risotto aux morilles sauvages était d'un niveau digne d'un grand restaurant !"
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-menu p-4 h-100 border shadow-sm">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info text-dark fw-bold rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                    ND
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-bold mb-0 text-dark">Nicolas Denis</h6>
                                    <small class="text-warning">★★★★★</small>
                                </div>
                                <span class="ms-auto text-muted small">Il y a 1 mois</span>
                            </div>
                            <p class="text-secondary card-text fs-6 mb-0">
                                "Pratique, rapide à réserver et absolument délicieux. Le service client asynchrone est top et le conditionnement respecte parfaitement la chaîne du froid."
                            </p>
                        </div>
                    </div>
                </div>
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
        let count = parseInt(localStorage.getItem('panier_count')) || 0;
        const cartCount = document.getElementById('cart-count');
        const notifZone = document.getElementById('notif-zone');
        
        if (cartCount) cartCount.textContent = count;

        document.querySelectorAll('.btn-add-cart').forEach(button => {
            button.addEventListener('click', (e) => {
                const menuName = e.target.getAttribute('data-name');
                count++;
                localStorage.setItem('panier_count', count);
                if (cartCount) cartCount.textContent = count;

                if (notifZone) {
                    notifZone.innerHTML = `
                        <div class="alert alert-success text-center alert-dismissible fade show shadow-sm rounded-3" role="alert">
                            <strong>${menuName}</strong> ajouté au panier !
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    setTimeout(() => { notifZone.innerHTML = ''; }, 3000);
                }
            });
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>