<?php
session_start();
require_once 'inc/db.php';
include 'inc/header.php';

// 1. SÉCURITÉ ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. ON RÉCUPÈRE LE PLAT À MODIFIER
if (!isset($_GET['id'])) {
    header("Location: admin.php"); // Si pas d'ID, on retourne au tableau
    exit;
}

$id = $_GET['id'];

// On va chercher les infos actuelles du menu dans la base
$stmt = $pdo->prepare("SELECT * FROM menu WHERE id = :id");
$stmt->execute(['id' => $id]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    die("Menu introuvable !");
}

// On récupère aussi les listes pour les menus déroulants
$themes = $pdo->query("SELECT * FROM theme")->fetchAll();
$regimes = $pdo->query("SELECT * FROM regime")->fetchAll();

// 3. TRAITEMENT DU FORMULAIRE (Quand on clique sur "Mettre à jour")
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $theme_id = $_POST['theme_id'];
    $regime_id = $_POST['regime_id'];

    // Mise à jour SQL
    $sql = "UPDATE menu SET 
            titre = :titre, 
            description = :description, 
            prix = :prix, 
            theme_id = :theme_id, 
            regime_id = :regime_id 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'titre' => $titre,
        'description' => $description,
        'prix' => $prix,
        'theme_id' => $theme_id,
        'regime_id' => $regime_id,
        'id' => $id
    ]);

    if ($result) {
        // Succès : retour au tableau de bord
        echo "<script>window.location.href='admin.php';</script>";
        exit;
    } else {
        $error = "Erreur lors de la modification.";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Modifier le menu : <?= htmlspecialchars($menu['titre']) ?></h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nom du plat</label>
                            <input type="text" name="titre" class="form-control" required value="<?= htmlspecialchars($menu['titre']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($menu['description']) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Prix (€)</label>
                                <input type="number" step="0.50" name="prix" class="form-control" required value="<?= $menu['prix'] ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Thème</label>
                                <select name="theme_id" class="form-select">
                                    <?php foreach($themes as $t): ?>
                                        <option value="<?= $t['id'] ?>" <?= ($t['id'] == $menu['theme_id']) ? 'selected' : '' ?>>
                                            <?= $t['nom'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Régime</label>
                                <select name="regime_id" class="form-select">
                                    <?php foreach($regimes as $r): ?>
                                        <option value="<?= $r['id'] ?>" <?= ($r['id'] == $menu['regime_id']) ? 'selected' : '' ?>>
                                            <?= $r['nom'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Mettre à jour</button>
                            <a href="admin.php" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>