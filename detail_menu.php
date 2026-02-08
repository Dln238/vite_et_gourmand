<?php
// 1. Démarrage de session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'inc/db.php';
include 'inc/header.php';

// 2. VÉRIFICATION DE L'ID
// Si pas d'ID dans l'URL (ex: detail_menu.php tout court), on renvoie vers la liste
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: menus.php');
    exit();
}

$id = $_GET['id'];

// 3. RÉCUPÉRATION DU MENU
try {
    // On prépare la requête pour éviter les injections SQL
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si l'ID ne correspond à rien (ex: id=9999)
    if (!$menu) {
        header('Location: menus.php');
        exit();
    }

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>

<div class="container py-5">
    
    <a href="menus.php" class="btn btn-outline-secondary mb-4">← Retour aux menus</a>

    <div class="row">
        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <?php if (!empty($menu['photo'])): ?>
                    <img src="<?= htmlspecialchars($menu['photo']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($menu['titre']) ?>">
                <?php else: ?>
                    <img src="https://placehold.co/600x400/orange/white?text=Menu" class="img-fluid rounded" alt="Image par défaut">
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <h1 class="display-5 fw-bold"><?= htmlspecialchars($menu['titre']) ?></h1>
            
            <h3 class="text-warning fw-bold my-3"><?= number_format($menu['prix'], 2) ?> € <span class="fs-6 text-muted">/ personne</span></h3>

            <p class="lead"><?= nl2br(htmlspecialchars($menu['description'])) ?></p>

            <hr>

            <div class="mb-4">
                <strong>Minimum de commande :</strong> 5 personnes<br>
                <strong>Type :</strong> 
                <?php 
                    if ($menu['theme_id'] == 1) echo "Menu de Noël";
                    elseif ($menu['theme_id'] == 2) echo "Mariage";
                    elseif ($menu['theme_id'] == 3) echo "Entreprise";
                    else echo "Classique";
                ?>
                <br>
                <strong>Allergènes possibles :</strong> Gluten, Lactose
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                
                <a href="commande.php?menu_id=<?= $menu['id'] ?>" class="btn btn-dark btn-lg w-100 shadow">
                    Passer la commande
                </a>

            <?php else: ?>
                
                <div class="alert alert-warning">
                    Vous devez être connecté pour commander ce menu.
                </div>
                <a href="login.php" class="btn btn-outline-dark w-100">
                    Se connecter pour commander
                </a>

            <?php endif; ?>

        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>