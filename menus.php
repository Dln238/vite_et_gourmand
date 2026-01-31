<?php
// 1. Connexion et Header
require_once 'inc/db.php';
include 'inc/header.php';

// 2. LOGIQUE DE FILTRE
// Par défaut, on veut tous les menus
$sql = "SELECT * FROM menu";
$titre_page = "Tous nos Menus";

// Si l'utilisateur a cliqué sur un filtre (ex: menus.php?theme=1)
if (isset($_GET['theme'])) {
    $theme_id = $_GET['theme'];
    
    // On change la requête pour ne prendre que ce thème
    $sql = "SELECT * FROM menu WHERE theme_id = :theme_id";
    
    // Petite astuce pour changer le titre de la page dynamiquement
    if ($theme_id == 1) $titre_page = "Nos Menus de Noël";
    if ($theme_id == 2) $titre_page = "Nos Menus Mariage";
    if ($theme_id == 3) $titre_page = "Nos Offres Entreprise";
}

// 3. ON EXÉCUTE LA REQUÊTE
try {
    $stmt = $pdo->prepare($sql);
    
    // Si on a un filtre, on l'envoie dans la requête
    if (isset($theme_id)) {
        $stmt->execute(['theme_id' => $theme_id]);
    } else {
        $stmt->execute(); // Sinon on exécute normalement (tout)
    }
    
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    echo "Erreur SQL";
}
?>

<div class="bg-light py-5">
    <div class="container">
        
        <h1 class="text-center mb-4"><?= $titre_page ?></h1>

        <div class="d-flex justify-content-center gap-2 mb-5">
          <div class="d-flex justify-content-center gap-2 mb-5">
            
            <a href="menus.php" class="btn <?= !isset($_GET['theme']) ? 'btn-dark' : 'btn-outline-dark' ?> rounded-pill px-4">
                Tout voir
            </a>

            <a href="menus.php?theme=1" class="btn <?= (isset($_GET['theme']) && $_GET['theme'] == 1) ? 'btn-danger' : 'btn-outline-danger' ?> rounded-pill px-4">
                🎄 Noël
            </a>

            <a href="menus.php?theme=2" class="btn <?= (isset($_GET['theme']) && $_GET['theme'] == 2) ? 'btn-warning' : 'btn-outline-warning' ?> rounded-pill px-4">
                💍 Mariage
            </a>

            <a href="menus.php?theme=3" class="btn <?= (isset($_GET['theme']) && $_GET['theme'] == 3) ? 'btn-primary' : 'btn-outline-primary' ?> rounded-pill px-4">
                💼 Entreprise
            </a>

        </div>
        </div>

        <?php if (empty($menus)): ?>
            <div class="alert alert-info text-center">
                Aucun menu trouvé dans cette catégorie pour le moment.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($menus as $menu): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm hover-card">
                            
                            <?php if (!empty($menu['photo'])): ?>
                                <img src="<?= htmlspecialchars($menu['photo']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Menu">
                            <?php else: ?>
                                <img src="https://placehold.co/400x250/orange/white?text=Menu" class="card-img-top" alt="Image par défaut">
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($menu['titre']) ?></h5>
                                <p class="card-text text-muted flex-grow-1"><?= htmlspecialchars(substr($menu['description'], 0, 100)) ?>...</p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <h4 class="text-warning fw-bold mb-0"><?= number_format($menu['prix'], 2) ?> €</h4>
                                    <?php if ($menu['regime_id'] == 2): ?>
                                        <span class="badge bg-success">Végétarien</span>
                                    <?php endif; ?>
                                </div>
                                
                                <a href="#" class="btn btn-dark mt-3 w-100">Commander</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include 'inc/footer.php'; ?>