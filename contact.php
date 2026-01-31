<?php
session_start();
require_once 'inc/db.php';
include 'inc/header.php';

// TRAITEMENT DU FORMULAIRE (Simulation)
$success = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ici, plus tard, on pourrait enregistrer le message en base de données ou envoyer un email.
    // Pour l'instant, on simule juste que tout s'est bien passé.
    $success = "Merci ! Votre message a bien été envoyé. Nous vous répondrons sous 24h.";
}
?>

<div class="bg-light py-5">
    <div class="container">
        <h1 class="text-center mb-5">Contactez-nous</h1>

        <div class="row g-5">
            <div class="col-md-6">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Nos Coordonnées</h3>
                        
                        <p class="mb-3">
                            <strong>📍 Adresse :</strong><br>
                            12 Rue de la Gourmandise<br>
                            33000 Bordeaux
                        </p>

                        <p class="mb-3">
                            <strong>📞 Téléphone :</strong><br>
                            <a href="tel:0556000000" class="text-decoration-none text-dark">05 56 00 00 00</a>
                        </p>

                        <p class="mb-3">
                            <strong>📧 Email :</strong><br>
                            <a href="mailto:contact@vite-et-gourmand.fr" class="text-decoration-none text-dark">contact@vite-et-gourmand.fr</a>
                        </p>

                        <hr>

                        <h5 class="mt-4">Horaires d'ouverture</h5>
                        <ul class="list-unstyled">
                            <li>Lundi - Vendredi : 9h00 - 20h00</li>
                            <li>Samedi : 10h00 - 22h00</li>
                            <li>Dimanche : Fermé</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Envoyez-nous un message</h3>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <?= $success ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Votre Nom</label>
                                <input type="text" name="nom" class="form-control" required placeholder="Jean Dupont">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Votre Email</label>
                                <input type="email" name="email" class="form-control" required placeholder="jean@exemple.com">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sujet</label>
                                <select name="sujet" class="form-select">
                                    <option>Demande de devis</option>
                                    <option>Question sur les menus</option>
                                    <option>Réclamation</option>
                                    <option>Autre</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea name="message" class="form-control" rows="5" required placeholder="Comment pouvons-nous vous aider ?"></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning btn-lg">Envoyer le message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-12">
                <img src="https://placehold.co/1200x400/png?text=Carte+Google+Maps+Bordeaux" class="img-fluid rounded shadow" alt="Plan d'accès">
            </div>
        </div>

    </div>
</div>

<?php include 'inc/footer.php'; ?>