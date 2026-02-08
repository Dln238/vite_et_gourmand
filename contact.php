<?php
// 1. Toujours la session en premier
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'inc/db.php';
include 'inc/header.php';

// 2. TRAITEMENT DU FORMULAIRE
$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification basique que les champs ne sont pas vides
    if (!empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['message'])) {
        // Ici, on simule l'envoi (dans la vraie vie, on utiliserait mail())
        $success = "Merci <strong>" . htmlspecialchars($_POST['nom']) . "</strong> ! Votre message a bien été envoyé. Nous vous répondrons sous 24h.";
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<div class="bg-light py-5 text-center mb-5">
    <div class="container">
        <h1 class="fw-bold">Contactez-nous</h1>
        <p class="text-muted">Une question sur un menu ? Un devis pour un mariage ?</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-5">
        
        <div class="col-md-6">
            <div class="h-100">
                <h3 class="mb-4">Nos Coordonnées</h3>
                <p class="text-muted mb-4">
                    Notre équipe est à votre écoute du lundi au samedi. N'hésitez pas à passer nous voir en boutique pour déguster nos nouveautés.
                </p>

                <div class="d-flex mb-3">
                    <div class="me-3 fs-4">📍</div>
                    <div>
                        <h6 class="fw-bold mb-0">Adresse</h6>
                        <p class="text-muted">12 Rue de la Gourmandise<br>33000 Bordeaux</p>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <div class="me-3 fs-4">📞</div>
                    <div>
                        <h6 class="fw-bold mb-0">Téléphone</h6>
                        <p class="text-muted">05 56 00 00 00</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="me-3 fs-4">📧</div>
                    <div>
                        <h6 class="fw-bold mb-0">Email</h6>
                        <p class="text-muted">contact@vite-et-gourmand.fr</p>
                    </div>
                </div>

                <div class="ratio ratio-16x9 shadow-sm rounded overflow-hidden">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2829.157833276663!2d-0.5791809842438646!3d44.83778997909874!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd5527c024f9b8c5%3A0x6e2697b003a27798!2sBordeaux!5e0!3m2!1sfr!2sfr!4v1645000000000!5m2!1sfr!2sfr" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow border-0 p-4">
                <h3 class="mb-4">Envoyez-nous un message</h3>

                <?php if ($success): ?>
                    <div class="alert alert-success d-flex align-items-center">
                        <span class="fs-4 me-2">✅</span> 
                        <div><?= $success ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Votre Nom</label>
                            <input type="text" name="nom" class="form-control bg-light" required placeholder="Jean Dupont">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Votre Email</label>
                            <input type="email" name="email" class="form-control bg-light" required placeholder="jean@exemple.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Sujet</label>
                        <select name="sujet" class="form-select bg-light">
                            <option>Demande de devis</option>
                            <option>Question sur les allergènes</option>
                            <option>Réclamation</option>
                            <option>Autre</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Message</label>
                        <textarea name="message" class="form-control bg-light" rows="5" required placeholder="Comment pouvons-nous vous aider ?"></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning btn-lg fw-bold shadow-sm">Envoyer le message</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include 'inc/footer.php'; ?>