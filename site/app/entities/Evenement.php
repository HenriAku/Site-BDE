<?php

class Evenement {
    private $id;
    private $nom;
    private $dateDebut;
    private $description;
    private $adresse;
    private $prix;
    public $note_moyenne;

    public function __construct($id, $nom, $dateDebut, $description, $adresse, $prix) {
        $this->id = $id;
        $this->nom = $nom;
        $this->dateDebut = $dateDebut;
        $this->description = $description;
        $this->adresse = $adresse;
        $this->prix = $prix;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getDateDebut() { return $this->dateDebut; }
    public function getDescription() { return $this->description; }
    public function getAdresse() { return $this->adresse; }
    public function getPrix() { return $this->prix; }

    public function getNote() {
        return rand(3, 5); // À remplacer par une vraie requête SQL
    }

    public function getNoteMoyenne() {
        return $this->note_moyenne;
    }
}
