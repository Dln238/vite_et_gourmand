<?php
session_start();

// 1. SÉCURITÉ : Vérification Admin
// Si le rôle n'existe pas ou n'est pas 'admin', on renvoie vers le login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'inc/db.php';
include 'inc/header.php';

// 2. RÉCUPÉRATION DES DONNÉES

// A. Les Commandes (avec le nom du client)
// On utilise une jointure (JOIN) pour avoir les infos de l'utilisateur qui a commandé
$sqlCommandes = "SELECT c.*, u.nom, u.prenom 
                 FROM commande c 
                 JOIN utilisateur u ON c.utilisateur_id = u.id 
                 ORDER BY c.date_prestation ASC";
try {
    $stmtComm = $pdo->query($sqlCommandes);
    $commandes = $stmtComm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $commandes = []; // Si erreur, tableau vide pour éviter le crash
}

// B. Les Menus
$stmt = $pdo->query("SELECT * FROM menu");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tableau de Bord Administrateur</h1>
        <a href="ajouter_menu.php" class="btn btn-success">+ Nouveau Menu</a>
    </div>

    <div class="card shadow mb-5">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">📦 Commandes à préparer</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Date Prestation</th>
                            <th>Client</th>
                            <th>Heure</th>
                            <th>Ville</th>
                            <th>Total</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($commandes)): ?>
                            <tr><td colspan="6" class="text-center">Aucune commande pour le moment.</td></tr>
                        <?php else: ?>
                            <?php foreach ($commandes as $cmd): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($cmd['date_prestation'])) ?></td>
                                    <td><?= htmlspecialchars($cmd['nom'] . ' ' . $cmd['prenom']) ?></td>
                                    <td><?= htmlspecialchars($cmd['heure_livraison']) ?></td>
                                    <td><?= htmlspecialchars($cmd['adresse_livraison']) ?></td>
                                    <td class="fw-bold"><?= number_format($cmd['prix_total'], 2) ?> €</td>
                                    <td>
                                        <span class="badge bg-info text-dark"><?= htmlspecialchars($cmd['statut']) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">🍴 Gestion des Menus</h5>
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
                                        <img src="<?= htmlspecialchars($menu['photo']) ?>" alt="Menu" class="img-thumbnail" style="height: 60px; object-fit: cover;">
                                    <?php else: ?>
                                        <span class="text-muted">Aucune</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($menu['titre']) ?></td>
                                <td class="text-success fw-bold"><?= number_format($menu['prix'], 2) ?> €</td>
                                <td>
                                    <a href="modifier_menu.php?id=<?= $menu['id'] ?>" class="btn btn-sm btn-primary me-2">Modifier</a>
                                    <a href="supprimer_menu.php?id=<?= $menu['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce menu ?');">
                                       Supprimer
                                    </a>
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