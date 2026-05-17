<?php

class Menu {
    // Propriétés privées (Encapsulation des données)
    private int $id;
    private string $titre;
    private string $description;
    private float $prix;
    private string $theme;
    private string $regime;

    // Constructeur pour initialiser un objet Menu
    public function __construct(int $id, string $titre, string $description, float $prix, string $theme, string $regime) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->prix = $prix;
        $this->theme = $theme;
        $this->regime = $regime;
    }

    // Accesseurs (Getters) pour permettre la lecture sécurisée des données
    public function getId(): int {
        return $this->id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPrix(): float {
        return $this->prix;
    }

    public function getTheme(): string {
        return $this->theme;
    }

    public function getRegime(): string {
        return $this->regime;
    }
}