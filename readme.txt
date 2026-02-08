# 🍽️ Vite & Gourmand - Application Web de Traiteur

Bienvenue sur le projet **Vite & Gourmand**, une application web dynamique permettant à un traiteur de gérer sa carte et ses commandes, et aux clients de réserver leurs repas en ligne.

Ce projet a été réalisé dans le cadre de mon examen (Session 2026).

---

## 🚀 Fonctionnalités Principales

### 👤 Partie Client (Front-Office)
- **Catalogue Visuel :** Présentation des menus avec photos et descriptions.
- **Filtres Dynamiques :** Tri des menus par événement (Noël, Mariage, Entreprise).
- **Compte Client :** Inscription et Connexion sécurisées.
- **Panier & Commande :** Ajout de menus au panier et validation de la commande en base de données.
- **Contact :** Formulaire de contact et localisation via carte interactive.
- **Responsive Design :** Interface adaptée aux mobiles, tablettes et ordinateurs.

### 🛡️ Partie Administrateur (Back-Office)
- **Sécurité :** Accès protégé réservé au rôle `admin`.
- **Tableau de Bord :** Visualisation des dernières commandes avec l'identité du client et le montant total.
- **Gestion des Menus (CRUD complet) :**
  - **Ajouter** un nouveau menu (titre, description, prix, image URL).
  - **Modifier** un menu existant.
  - **Supprimer** un menu (avec confirmation de sécurité).

---

## 🛠️ Stack Technique

- **Langage Back-end :** PHP 8 (Natif, architecture procédurale).
- **Base de Données :** MySQL / MariaDB via PDO.
- **Front-end :** HTML5, CSS3, Framework Bootstrap 5.3.
- **Serveur local :** Compatible XAMPP / WAMP / MAMP.

---

## ⚙️ Installation et Déploiement

Suivez ces étapes pour lancer le projet sur votre machine locale :

### 1. Prérequis
- Avoir un environnement serveur local (ex: XAMPP).
- Placez le dossier du projet `vite_et_gourmand` dans le répertoire web (`htdocs` ou `www`).

### 2. Base de Données (Étape Cruciale)
1. Ouvrez **phpMyAdmin** (ex: `http://localhost/phpmyadmin`).
2. Créez une nouvelle base de données nommée : `vite_et_gourmand` (encodage `utf8mb4_general_ci`).
3. Cliquez sur l'onglet **Importer**.
4. Sélectionnez le fichier **`vite_et_gourmand.sql`** situé à la racine de ce projet.
5. Exécutez l'importation pour créer les tables et les données de test.

### 3. Configuration
Vérifiez que vos identifiants dans le fichier `inc/db.php` correspondent à votre configuration locale (par défaut sur XAMPP) :
```php
$host = 'localhost';
$dbname = 'vite_et_gourmand';
$username = 'root';
$password = ''; // Laisser vide sur Windows

🔑 Identifiants de Test (Jury)
Pour tester l'application, des comptes sont pré-configurés dans la base de données :

👨‍🍳 Compte Administrateur
(Permet d'accéder au dashboard admin.php et de gérer les stocks)

Email : admin@gmail.com

Mot de passe : 1234567890

👤 Compte Client
(Permet de passer une commande)

Email : client@test.com

Mot de passe : 1234567890

- Structure du Projet
vite_et_gourmand/
├── assets/             # Fichiers CSS et images statiques
├── documentation/      # Maquettes, wireframes et captures d'écran du projet
├── inc/                # Fichiers inclus (Connexion BDD, Header, Footer)
├── admin.php           # Tableau de bord Administrateur
├── index.php           # Page d'accueil
├── menus.php           # Catalogue filtrable
├── contact.php         # Page de contact
├── login.php           # Page de connexion
├── register.php        # Page d'inscription
├── panier.php          # Gestion du panier
├── commande.php        # Traitement de la commande
└── vite_et_gourmand.sql # Fichier d'export de la Base de Données


Auteur
Projet développé par TAVARES DE PINA Dylan.