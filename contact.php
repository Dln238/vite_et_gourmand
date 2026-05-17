<?php
// 1. Toujours la session en premier
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connexion à la base de données
require_once 'INC/db.php';

// 2. TRAITEMENT DU FORMULAIRE
$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['message'])) {
        $success = "Merci <strong>" . htmlspecialchars($_POST['nom']) . "</strong> ! Votre message a bien été envoyé. Nous vous répondrons sous 24h.";
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous - Vite & Gourmand</title>
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
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1534536281715-e28d76689b4d?q=80&w=1920') no-repeat center center/cover;
            color: white;
            padding: 60px 0;
            margin-bottom: 50px;
        }
        .card-contact {
            border: none;
            border-radius: 16px;
            background: #ffffff;
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
                    <a class="nav-link px-3 active" href="contact.php">Contact</a>
                    <a class="btn btn-custom ms-2 px-4 shadow-sm" href="login.php">Panier (<span id="cart-count">0</span>)</a>
                </div>
            </div>
        </nav>

        <div class="hero-section-small text-center shadow-sm">
            <div class="container py-2">
                <h1 class="fw-bold mb-2">Contactez-nous</h1>
                <p class="lead fs-5 text-light-50 mb-0">Une question sur un menu ? Un devis pour un mariage ? Nos équipes vous répondent.</p>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row g-5">
                
                <div class="col-md-6">
                    <div class="h-100 p-2">
                        <h3 class="fw-bold text-dark mb-4">Nos Coordonnées</h3>
                        <p class="text-muted mb-4 fs-6">
                            Notre équipe est à votre écoute du lundi au samedi. N'hésitez pas à passer nous voir en boutique pour déguster nos nouveautés.
                        </p>

                        <div class="d-flex mb-4">
                            <div class="me-3 fs-3 text-warning">📍</div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Adresse</h6>
                                <p class="text-muted mb-0">12 Rue de la Gourmandise<br>33000 Bordeaux</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="me-3 fs-3 text-warning">📞</div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Téléphone</h6>
                                <p class="text-muted mb-0">05 56 00 00 00</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="me-3 fs-3 text-warning">📧</div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Email</h6>
                                <p class="text-muted mb-0">contact@vite-et-gourmand.fr</p>
                            </div>
                        </div>

                        <div class="ratio ratio-16x9 shadow-sm rounded-4 overflow-hidden mt-4">
                            <iframe 
                                src="https://maps.google.com/maps?q=Bordeaux&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-contact shadow-sm p-4 p-lg-5">
                        <h3 class="fw-bold text-dark mb-4">Envoyez-nous un message</h3>

                        <?php if ($success): ?>
                            <div class="alert alert-success d-flex align-items-center rounded-3 shadow-sm mb-4">
                                <span class="fs-4 me-2">✅</span> 
                                <div><?= $success ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger rounded-3 shadow-sm mb-4">
                                <strong>Erreur :</strong> <?= $error ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Votre Nom</label>
                                    <input type="text" name="nom" class="form-control bg-light py-2 rounded-3" required placeholder="Jean Dupont">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold text-secondary">Votre Email</label>
                                    <input type="email" name="email" class="form-control bg-light py-2 rounded-3" required placeholder="jean@exemple.com">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Sujet</label>
                                <select name="sujet" class="form-select bg-light py-2 rounded-3">
                                    <option>Demande de devis</option>
                                    <option>Question sur les allergènes</option>
                                    <option>Réclamation</option>
                                    <option>Autre</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary">Message</label>
                                <textarea name="message" class="form-control bg-light rounded-3" rows="5" required placeholder="Comment pouvons-nous vous aider ?"></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark btn-lg fw-semibold rounded-3 py-3 shadow-sm">Envoyer le message</button>
                            </div>
                        </form>
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