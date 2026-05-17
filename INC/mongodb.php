<?php
// Connexion au conteneur MongoDB (NoSQL)
try {
    // "mongodb" correspond au nom du service défini dans notre docker-compose.yml
    $mongo = new MongoDB\Driver\Manager("mongodb://mongodb:27017");
} catch (MongoDB\Driver\Exception\Exception $e) {
    // Interception de l'échec de connexion (Fail-Safe)
    die("Erreur critique de connexion à MongoDB : " . $e->getMessage());
}
?>