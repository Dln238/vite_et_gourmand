<?php
session_start();
// SÉCURITÉ : Si on n'est pas admin, on vire !
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'inc/db.php';
include 'inc/header.php';

// ON RÉCUPÈRE LES CATÉGORIES (pour les listes déroulantes)
$themes = $pdo->query("SELECT * FROM theme")->fetchAll();
$regimes = $pdo->query("SELECT * FROM regime")->fetchAll();

// TRAITEMENT DU FORMULAIRE (Quand on clique sur "Enregistrer")
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère ce que l'admin a écrit
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $theme_id = $_POST['theme_id'];
    $regime_id = $_POST['regime_id'];
    
    // Pour la photo, on met une image par défaut pour simplifier l'exercice
    // (Plus tard, on pourra apprendre à uploader de vrais fichiers)
    $photo = "https://placehold.co/400x300/333/white?text=" . urlencode($titre);

    // REQUÊTE SQL D'INSERTION
    $sql = "INSERT INTO menu (titre, description, prix, photo, theme_id, regime_id) 
            VALUES (:titre, :description, :prix, :photo, :theme_id, :regime_id)";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'titre' => $titre,
        'description' => $description,
        'prix' => $prix,
        'photo' => $photo,
        'theme_id' => $theme_id,
        'regime_id' => $regime_id
    ]);

    if ($result) {
        // Si ça marche, on retourne au tableau de bord avec un message de succès (optionnel)
        header("Location: admin.php");
        exit;
    } else {
        $error = "Une erreur est survenue lors de l'enregistrement.";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Ajouter un nouveau plat</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nom du plat</label>
                            <input type="text" name="titre" class="form-control" required placeholder="Ex: Risotto aux cèpes">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" required placeholder="Détaillez les ingrédients..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Prix (€)</label>
                                <input type="number" step="0.50" name="prix" class="form-control" required placeholder="15.00">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Thème</label>
                                <select name="theme_id" class="form-select">
                                    <?php foreach($themes as $t): ?>
                                        <option value="<?= $t['id'] ?>"><?= $t['nom'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Régime</label>
                                <select name="regime_id" class="form-select">
                                    <?php foreach($regimes as $r): ?>
                                        <option value="<?= $r['id'] ?>"><?= $r['nom'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg">Enregistrer le menu</button>
                            <a href="admin.php" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>