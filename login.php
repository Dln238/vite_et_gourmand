<?php
// On démarre la session
session_start();

// Attention aux majuscules/minuscules selon ton dossier réel (INC ou inc)
require_once 'INC/db.php'; 
include 'INC/header.php';

$error = null;
$success = null;

// Si on arrive ici après une inscription réussie (?success=1)
if (isset($_GET['success'])) {
    $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
}

// TRAITEMENT DU FORMULAIRE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. On cherche l'utilisateur par son email
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. VÉRIFICATION SÉCURISÉE
    // On vérifie si l'utilisateur existe ET si le mot de passe correspond au cryptage
    if ($user && password_verify($password, $user['mot_de_passe'])) {
        
        // C'est gagné ! On enregistre les infos en session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['nom'] = $user['nom']; // Utile pour la suite

        // Redirection : 
        // Si c'est un admin ou employé -> vers admin.php
        // Si c'est un client -> vers l'accueil (index.php) ou profil
        if ($user['role'] == 'client') {
             header("Location: index.php");
        } else {
             header("Location: admin.php");
        }
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
                    <h4 class="mb-0">Connexion Espace Client & Pro</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

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
                    
                    <hr>
                    <p class="text-center mt-3">
                        Pas encore de compte ? <br>
                        <a href="inscription.php" class="btn btn-outline-warning mt-2">Créer un compte client</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'INC/footer.php'; ?>