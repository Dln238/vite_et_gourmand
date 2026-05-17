
-- Création de la table utilisateur
CREATE TABLE IF NOT EXISTS utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client') DEFAULT 'client',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Création de la table menu
CREATE TABLE IF NOT EXISTS menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    theme VARCHAR(50),
    regime VARCHAR(50),
    image_url VARCHAR(255),
    disponible BOOLEAN DEFAULT TRUE
);

-- Création de la table commande avec contraintes d'intégrité
CREATE TABLE IF NOT EXISTS commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    statut ENUM('en_attente', 'validee', 'livree', 'annulee') DEFAULT 'en_attente',
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE
);

-- Création de la table de liaison pour le détail des commandes
CREATE TABLE IF NOT EXISTS ligne_commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    menu_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commande(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menu(id) ON DELETE CASCADE
);

-- ACTION DE NETTOYAGE : Vide la table menu pour supprimer les anciennes lignes cassées
TRUNCATE TABLE menu;

-- Insertion des lignes de test propres sans accents
INSERT INTO menu (titre, description, prix, theme, regime, image_url) VALUES
('Menu Festif', 'Entree: Foie gras | Plat: Chapon roti | Dessert: Buche', 25.00, 'Noel', 'Classique', 'default.jpg'),
('Menu Vegetal', 'Entree: Veloute | Plat: Risotto | Dessert: Tarte', 20.00, 'Printemps', 'Vegetarien', 'default.jpg');