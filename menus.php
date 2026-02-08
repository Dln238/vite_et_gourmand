<?php
// 1. Démarrage de session (Toujours en premier)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'inc/db.php';
include 'inc/header.php';

// 2. LOGIQUE DE FILTRE (On garde ta super logique PHP)
$sql = "SELECT * FROM menu";
$titre_page = "Toute la carte";
$params = [];

// Si l'utilisateur a cliqué sur un filtre
if (isset($_GET['theme']) && !empty($_GET['theme'])) {
    $theme_id = $_GET['theme'];
    $sql .= " WHERE theme_id = :theme_id";
    $params['theme_id'] = $theme_id;
    
    if ($theme_id == 1) $titre_page = "🎄 Nos Menus de Noël";
    if ($theme_id == 2) $titre_page = "💍 Mariages & Réceptions";
    if ($theme_id == 3) $titre_page = "💼 Offres Entreprises";
}

// 3. ON EXÉCUTE LA REQUÊTE
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Erreur SQL : " . $e->getMessage();
}
?>

<div class="bg-light py-5 text-center border-bottom">
    <div class="container">
        <h1 class="fw-bold"><?= $titre_page ?></h1>
        <p class="text-muted">Découvrez nos créations faites maison.</p>
        
        <div class="btn-group mt-3 shadow-sm" role="group">
            <a href="menus.php" class="btn btn-outline-dark <?= !isset($_GET['theme']) ? 'active' : '' ?>">Tout</a>
            <a href="menus.php?theme=1" class="btn btn-outline-danger <?= (isset($_GET['theme']) && $_GET['theme'] == 1) ? 'active' : '' ?>">Noël</a>
            <a href="menus.php?theme=2" class="btn btn-outline-warning text-dark <?= (isset($_GET['theme']) && $_GET['theme'] == 2) ? 'active' : '' ?>">Mariage</a>
            <a href="menus.php?theme=3" class="btn btn-outline-primary <?= (isset($_GET['theme']) && $_GET['theme'] == 3) ? 'active' : '' ?>">Entreprise</a>
        </div>
    </div>
</div>

<div class="container py-5">
    <?php if (empty($menus)): ?>
        <div class="alert alert-info text-center p-5">
            <h4>Oups !</h4>
            <p>Aucun menu trouvé dans cette catégorie pour le moment.</p>
            <a href="menus.php" class="btn btn-dark mt-3">Retour à la carte</a>
        </div>
    <?php else: ?>
        
        <div class="row">
            <?php foreach ($menus as $menu): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border">
                        
                        <?php if (!empty($menu['photo'])): ?>
                            <img src="<?= htmlspecialchars($menu['photo']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Menu">
                        <?php else: ?>
                            <img src="https://placehold.co/600x400/eee/333?text=<?= urlencode($menu['titre']) ?>" class="card-img-top" alt="Image Menu">
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0"><?= htmlspecialchars($menu['titre']) ?></h5>
                                <?php if ($menu['regime_id'] == 2): ?>
                                    <span class="badge bg-success">Végé</span>
                                <?php endif; ?>
                            </div>

                            <p class="card-text text-muted small flex-grow-1">
                                <?= htmlspecialchars(substr($menu['description'], 0, 100)) ?>...
                            </p>
                            
                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 text-primary fw-bold"><?= number_format($menu['prix'], 2) ?> €</span>
                                
                                <a href="detail_menu.php?id=<?= $menu['id'] ?>" class="btn btn-sm btn-dark">
                                    Voir le détail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<?php include 'inc/footer.php'; ?>