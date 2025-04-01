<?php

class Evenement {
    private $id;
    private $nom;
    private $dateDebut;
    private $description;
    private $adresse;
    private $prix;
    private $image;
    public $note_moyenne;
    public $nb_avis;

    public function __construct($id, $nom, $dateDebut, $description, $adresse, $prix, $image = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->dateDebut = $dateDebut;
        $this->description = $description;
        $this->adresse = $adresse;
        $this->prix = $prix;
        $this->image = $image;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getDateDebut() { return $this->dateDebut; }
    public function getDescription() { return $this->description; }
    public function getAdresse() { return $this->adresse; }
    public function getPrix() { return $this->prix; }
    public function getImage() { return $this->image ?? 'default-event.jpg'; }
    public function getNoteMoyenne() { return $this->note_moyenne; }
    public function getNbAvis() { return $this->nb_avis; }
}
