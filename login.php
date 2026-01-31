<?php
// On démarre la session (obligatoire pour rester connecté de page en page)
session_start();

require_once 'inc/db.php';
include 'inc/header.php';

$error = null;

// Si le formulaire est envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // On cherche l'utilisateur dans la base
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // VÉRIFICATION DU MOT DE PASSE
    // On regarde si l'utilisateur existe ET si le mot de passe est bon (1234)
    if ($user && $password === $user['mot_de_passe']) {
        // C'est gagné ! On enregistre les infos dans la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['prenom'] = $user['prenom'];

        // On redirige vers l'accueil
        header("Location: index.php");
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">Connexion Espace Pro</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Se connecter</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>