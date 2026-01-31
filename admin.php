<?php
session_start();

// SÉCURITÉ : Si on n'est pas connecté ou pas admin, DEHORS !
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'inc/db.php';
include 'inc/header.php';

// On récupère la liste des menus pour les afficher dans un tableau
$stmt = $pdo->query("SELECT * FROM menu");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tableau de Bord Administrateur</h1>
        <a href="ajouter_menu.php" class="btn btn-success">+ Nouveau Menu</a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Gestion des Menus</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Titre</th>
                            <th>Prix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menus as $menu): ?>
                            <tr>
                                <td style="width: 100px;">
                                    <?php if (!empty($menu['photo'])): ?>
                                        <img src="<?= htmlspecialchars($menu['photo']) ?>" alt="Menu" class="img-thumbnail" style="height: 60px;">
                                    <?php else: ?>
                                        <span class="text-muted">Aucune</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($menu['titre']) ?></td>
                                <td class="text-success fw-bold"><?= number_format($menu['prix'], 2) ?> €</td>
                                <td>
                                    <a href="modifier_menu.php?id=<?= $menu['id'] ?>" class="btn btn-sm btn-primary me-2">Modifier</a>
<a href="supprimer_menu.php?id=<?= $menu['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce menu ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>