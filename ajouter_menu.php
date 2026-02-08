<?php
session_start();
require_once 'inc/db.php';
include 'inc/header.php';

// 1. SÉCURITÉ ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// On récupère les listes pour les menus déroulants (Thèmes et Régimes)
$themes = $pdo->query("SELECT * FROM theme")->fetchAll();
$regimes = $pdo->query("SELECT * FROM regime")->fetchAll();

// 2. TRAITEMENT DU FORMULAIRE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $photo = $_POST['photo']; // URL de l'image
    $theme_id = $_POST['theme_id'];
    $regime_id = $_POST['regime_id'];

    // Insertion SQL
    $sql = "INSERT INTO menu (titre, description, prix, photo, theme_id, regime_id) 
            VALUES (:titre, :description, :prix, :photo, :theme_id, :regime_id)";
    
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            'titre' => $titre,
            'description' => $description,
            'prix' => $prix,
            'photo' => $photo,
            'theme_id' => $theme_id,
            'regime_id' => $regime_id
        ]);

        // Succès : retour au tableau de bord
        header("Location: admin.php");
        exit;

    } catch (PDOException $e) {
        $error = "Erreur lors de l'ajout : " . $e->getMessage();
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Ajouter un nouveau menu</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nom du plat</label>
                            <input type="text" name="titre" class="form-control" required placeholder="Ex: Pavé de Saumon">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" required placeholder="Détail des ingrédients..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">URL de la photo</label>
                            <input type="text" name="photo" class="form-control" placeholder="https://...">
                            <div class="form-text">Vous pouvez mettre une URL d'image (Google Images, etc.)</div>
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
                            <button type="submit" class="btn btn-success btn-lg">Ajouter le menu</button>
                            <a href="admin.php" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>