<?php
// On inclut la connexion à la base de données
require_once 'INC/db.php'; 

$message = ""; 

// Si le formulaire est envoyé
if (!empty($_POST)) {
    // 1. Nettoyage des données
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $ville = htmlspecialchars($_POST['ville']);
    $cp = htmlspecialchars($_POST['code_postal']);
    $password = $_POST['password'];

    // 2. Vérification mot de passe (10 car. min selon le sujet)
    if (strlen($password) < 10) {
        $message = '<div class="alert error" style="color:red; background:#ffcccc; padding:10px;">Le mot de passe est trop court (10 caractères min).</div>';
    } else {
        // 3. Hachage du mot de passe (Sécurité)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // 4. Insertion dans ta table 'utilisateur'
        // Note bien : on utilise 'mot_de_passe' comme vu sur ta capture
        $sql = "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, telephone, adresse, ville, code_postal, role) 
                VALUES (:nom, :prenom, :email, :mdp, :telephone, :adresse, :ville, :cp, 'client')";
        
        $query = $pdo->prepare($sql);
        
        try {
            $query->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'mdp' => $passwordHash,
                'telephone' => $telephone,
                'adresse' => $adresse,
                'ville' => $ville,
                'cp' => $cp
            ]);
            
            // Si ça marche, on redirige vers la connexion avec un message de succès
            header('Location: login.php?success=1');
            exit();
            
        } catch (PDOException $e) {
            $message = '<div class="alert error" style="color:red;">Erreur : Cet email est peut-être déjà utilisé.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Vite & Gourmand</title>
    <link rel="stylesheet" href="ASSETS/css/style.css"> 
    <style>
        /* Un peu de CSS juste pour rendre le formulaire propre */
        .container-form { max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn-submit { background-color: #28a745; color: white; padding: 10px 20px; border: none; cursor: pointer; width: 100%; font-size: 16px; }
        .btn-submit:hover { background-color: #218838; }
    </style>
</head>
<body>

    <?php include 'INC/header.php'; ?>

    <main class="container-form">
        <h1 style="text-align:center;">Créer un compte Client</h1>
        
        <?= $message; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Nom :</label>
                <input type="text" name="nom" required>
            </div>
            <div class="form-group">
                <label>Prénom :</label>
                <input type="text" name="prenom" required>
            </div>

            <div class="form-group">
                <label>Email :</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Téléphone :</label>
                <input type="text" name="telephone" required>
            </div>

            <div class="form-group">
                <label>Adresse :</label>
                <textarea name="adresse" required></textarea>
            </div>
            <div class="form-group">
                <label>Code Postal :</label>
                <input type="text" name="code_postal" required>
            </div>
            <div class="form-group">
                <label>Ville :</label>
                <input type="text" name="ville" required>
            </div>

            <div class="form-group">
                <label>Mot de passe (10 caractères min) :</label>
                <input type="password" name="password" required minlength="10">
            </div>

            <button type="submit" class="btn-submit">S'inscrire</button>
        </form>
        
        <p style="text-align:center; margin-top:20px;">
            Déjà inscrit ? <a href="login.php">Connectez-vous ici</a>
        </p>
    </main>

    <?php include 'INC/footer.php'; ?>

</body>
</html>