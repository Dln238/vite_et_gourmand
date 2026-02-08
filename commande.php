<?php
// 1. Démarrage de session (Toujours en premier)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'inc/db.php';
include 'inc/header.php';

// 2. VÉRIFICATIONS DE SÉCURITÉ
// Si le client n'est pas connecté, oust !
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Si pas d'ID de menu, retour aux menus
if (!isset($_GET['menu_id']) || empty($_GET['menu_id'])) {
    header('Location: menus.php');
    exit();
}

$menu_id = $_GET['menu_id'];
$user_id = $_SESSION['user_id'];
$message = "";

// 3. RÉCUPÉRATION DES INFOS (Client + Menu)
try {
    // Infos du Menu
    $stmtMenu = $pdo->prepare("SELECT * FROM menu WHERE id = :id");
    $stmtMenu->execute(['id' => $menu_id]);
    $menu = $stmtMenu->fetch(PDO::FETCH_ASSOC);

    // Infos du Client (pour pré-remplir)
    $stmtUser = $pdo->prepare("SELECT * FROM utilisateur WHERE id = :id");
    $stmtUser->execute(['id' => $user_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Erreur de données : " . $e->getMessage());
}

// 4. TRAITEMENT DU FORMULAIRE (Quand on clique sur VALIDER)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Récupération des données du formulaire
    $date_prestation = $_POST['date_prestation'];
    $heure_livraison = $_POST['heure_livraison'];
    $adresse_livraison = $_POST['adresse_livraison'];
    $quantite = intval($_POST['quantite']);
    $distance = intval($_POST['distance']); // Simulation des km pour le calcul
    
    // RÈGLES DE GESTION (Calculs PHP pour être sûr du prix)
    // Prix de base
    $prix_menu = $menu['prix'];
    $total_menu = $prix_menu * $quantite;

    // Réduction : 10% si 5 personnes de plus que le minimum (on suppose min = 10 pour l'exemple)
    $min_personne = 10; 
    if ($quantite >= ($min_personne + 5)) {
        $total_menu = $total_menu * 0.90; // -10%
    }

    // Livraison : 5€ de base + 0.59€ par km
    $frais_livraison = 5 + ($distance * 0.59);

    $prix_total_commande = $total_menu + $frais_livraison;

    // 5. ENREGISTREMENT EN BASE DE DONNÉES
    try {
        // A. On insère la commande principale
        // Note : Je mets le statut à 'En attente' par défaut
        $sqlCommande = "INSERT INTO commande (utilisateur_id, date_commande, date_prestation, heure_livraison, adresse_livraison, prix_total, statut) 
                        VALUES (:user_id, NOW(), :date_p, :heure_l, :adresse, :prix, 'En attente')";
        
        $stmtCmd = $pdo->prepare($sqlCommande);
        $stmtCmd->execute([
            'user_id' => $user_id,
            'date_p' => $date_prestation,
            'heure_l' => $heure_livraison,
            'adresse' => $adresse_livraison,
            'prix' => $prix_total_commande
        ]);

        // On récupère l'ID de la commande qu'on vient de créer
        $commande_id = $pdo->lastInsertId();

        // B. On insère le détail (Ligne de commande)
        $sqlLigne = "INSERT INTO ligne_commande (commande_id, menu_id, quantite) VALUES (:cmd_id, :menu_id, :qty)";
        $stmtLigne = $pdo->prepare($sqlLigne);
        $stmtLigne->execute([
            'cmd_id' => $commande_id,
            'menu_id' => $menu_id,
            'qty' => $quantite
        ]);

        // C. Succès ! On affiche un message (ou redirection)
        $message = "<div class='alert alert-success'>🎉 Commande validée avec succès ! Merci de votre confiance.</div>";

    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Erreur lors de la commande : " . $e->getMessage() . "</div>";
    }
}
?>

<div class="container py-5">
    <h2 class="mb-4">Finaliser votre commande</h2>
    
    <?= $message ?>

    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Votre Menu</span>
            </h4>
            <ul class="list-group mb-3 shadow-sm">
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0"><?= htmlspecialchars($menu['titre']) ?></h6>
                        <small class="text-muted">Prix unitaire</small>
                    </div>
                    <span class="text-muted"><?= number_format($menu['prix'], 2) ?> €</span>
                </li>
                <li class="list-group-item d-flex justify-content-between bg-light">
                    <div class="text-success">
                        <h6 class="my-0">Réduction éventuelle</h6>
                        <small>Si +5 pers. au dessus du min.</small>
                    </div>
                    <span class="text-success" id="promo-display">-0 €</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (EUR)</span>
                    <strong id="total-display">0.00 €</strong>
                </li>
            </ul>
        </div>

        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Informations de livraison</h4>
            
            <form method="POST" class="needs-validation shadow p-4 rounded bg-white">
                
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" readonly>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email <span class="text-muted">(Pour la confirmation)</span></label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Adresse de livraison</label>
                        <textarea name="adresse_livraison" class="form-control" required><?= htmlspecialchars($user['adresse']) . ', ' . htmlspecialchars($user['code_postal']) . ' ' . htmlspecialchars($user['ville']) ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date de la prestation</label>
                        <input type="date" name="date_prestation" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Heure souhaitée</label>
                        <input type="time" name="heure_livraison" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Distance depuis Bordeaux (km)</label>
                        <input type="number" id="distance" name="distance" class="form-control" value="0" min="0" required onchange="calculerPrix()">
                        <small class="text-muted">0.59€ / km supplémentaire</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nombre de personnes</label>
                        <input type="number" id="quantite" name="quantite" class="form-control" value="10" min="1" required onchange="calculerPrix()">
                    </div>
                 </div>

                <hr class="my-4">

                <button class="w-100 btn btn-warning btn-lg fw-bold" type="submit">Valider et Payer à la livraison</button>
            </form>
        </div>
    </div>
</div>

<script>
    // On récupère le prix du menu depuis PHP
    const prixUnitaire = <?= $menu['prix'] ?>;
    
    function calculerPrix() {
        let quantite = document.getElementById('quantite').value;
        let distance = document.getElementById('distance').value;
        
        // Calcul Menu
        let totalMenu = prixUnitaire * quantite;
        let reduction = 0;

        // Règle : Réduction 10% si quantité >= 15 (exemple : min 10 + 5)
        if (quantite >= 15) {
            reduction = totalMenu * 0.10;
            totalMenu = totalMenu - reduction;
        }

        // Calcul Livraison (5€ base + 0.59/km)
        let fraisLivraison = 5 + (distance * 0.59);

        let grandTotal = totalMenu + fraisLivraison;

        // Affichage
        document.getElementById('promo-display').innerText = "- " + reduction.toFixed(2) + " €";
        document.getElementById('total-display').innerText = grandTotal.toFixed(2) + " €";
    }

    // On lance le calcul au chargement de la page
    calculerPrix();
</script>

<?php include 'inc/footer.php'; ?>