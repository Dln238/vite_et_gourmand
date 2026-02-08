<?php
// 1. Toujours la session en premier
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'inc/db.php'; // On a besoin de la BDD pour afficher les plats du moment !
include 'inc/header.php';

// 2. RÉCUPÉRATION DE 3 MENUS AU HASARD (C'est le côté "Plats à la Une")
// ORDER BY RAND() permet de changer les plats à chaque rechargement de page.
$stmt = $pdo->query("SELECT * FROM menu ORDER BY RAND() LIMIT 3");
$menus_vedettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="text-white text-center py-5 d-flex align-items-center justify-content-center" 
     style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1555244162-803834f70033?w=1920'); 
            background-size: cover; background-position: center; min-height: 60vh;">
    <div class="container">
        <h1 class="display-2 fw-bold mb-3">Vite & Gourmand</h1>
        <p class="lead mb-4 fs-3">L'excellence du traiteur livrée chez vous.</p>
        <p class="fs-5 mb-5">Mariages, Entreprises, Fêtes... Nous sublimons vos événements.</p>
        <a href="menus.php" class="btn btn-warning btn-lg px-5 py-3 fw-bold shadow-lg rounded-pill">
            Voir notre Carte ➔
        </a>
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?w=800" 
                     alt="L'équipe en cuisine" class="img-fluid rounded-4 shadow-lg">
            </div>
            <div class="col-md-6 ps-md-5">
                <h5 class="text-warning text-uppercase fw-bold ls-2">Notre Histoire</h5>
                <h2 class="mb-4 display-6 fw-bold">Des produits frais, une passion intacte.</h2>
                <p class="lead text-muted">Depuis 25 ans, Julie et José mettent leur savoir-faire au service de vos papilles à Bordeaux.</p>
                <p>Nous sélectionnons nos produits chaque matin au marché des Capucins. Pas de surgelé, pas de conserves : uniquement du fait-maison pour garantir une explosion de saveurs.</p>
                
                <div class="row mt-4 text-center">
                    <div class="col-4">
                        <h3 class="fw-bold text-dark">25</h3>
                        <small class="text-muted">Ans d'expérience</small>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold text-dark">500+</h3>
                        <small class="text-muted">Événements</small>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold text-dark">100%</h3>
                        <small class="text-muted">Frais & Local</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h5 class="text-warning text-uppercase fw-bold">À découvrir</h5>
            <h2 class="fw-bold">Nos Menus du Moment</h2>
            <p class="text-muted">Sélectionnés juste pour vous parmi nos meilleures créations.</p>
        </div>

        <div class="row">
            <?php foreach ($menus_vedettes as $menu): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <?php 
                            $imgSrc = !empty($menu['photo']) ? htmlspecialchars($menu['photo']) : "https://loremflickr.com/600/400/food";
                        ?>
                        <div style="height: 250px; overflow: hidden;">
                            <img src="<?= $imgSrc ?>" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="Menu">
                        </div>

                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($menu['titre']) ?></h5>
                            <p class="card-text text-muted flex-grow-1">
                                <?= htmlspecialchars(substr($menu['description'], 0, 80)) ?>...
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="h5 text-warning fw-bold mb-0"><?= number_format($menu['prix'], 2) ?> €</span>
                                <a href="detail_menu.php?id=<?= $menu['id'] ?>" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                    Voir ➔
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="menus.php" class="btn btn-dark px-4 py-2">Voir toute la carte</a>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Ils se sont régalés</h2>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card p-4 border-0 shadow-sm h-100 text-center bg-light">
                    <div class="text-warning fs-4 mb-2">★★★★★</div>
                    <p class="fst-italic text-muted">"Un service impeccable pour notre mariage. Les invités parlent encore du buffet !"</p>
                    <h6 class="fw-bold mt-3">- Sophie M.</h6>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card p-4 border-0 shadow-sm h-100 text-center bg-light">
                    <div class="text-warning fs-4 mb-2">★★★★☆</div>
                    <p class="fst-italic text-muted">"Très bon rapport qualité-prix. Les plats étaient chauds et livrés à l'heure."</p>
                    <h6 class="fw-bold mt-3">- Pierre D.</h6>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card p-4 border-0 shadow-sm h-100 text-center bg-light">
                    <div class="text-warning fs-4 mb-2">★★★★★</div>
                    <p class="fst-italic text-muted">"Le menu de Noël était divin. Je recommande les yeux fermés."</p>
                    <h6 class="fw-bold mt-3">- Claire L.</h6>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'inc/footer.php'; ?>