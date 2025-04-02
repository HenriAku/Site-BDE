<?php

class Participation {
    private $nom_etu;
    private $prenom_etu;
    private $nom_event;
    private $dateDebut;
    private $prix;
    private $a_payer;
    private static $idParticipationIncr = 0;
    private $idParticipation;
    private $n_etu;
    private $n_event;

    public function __construct($nom_etu, $prenom_etu, $nom_event, $dateDebut, $prix, $a_payer, $n_etu, $n_event) {
        $this->nom_etu = $nom_etu;
        $this->prenom_etu = $prenom_etu;
        $this->nom_event = $nom_event;
        $this->dateDebut = $dateDebut;
        $this->prix = $prix;
        $this->a_payer = $a_payer;
        $this->idParticipation = self::$idParticipationIncr++;
        $this->n_etu = $n_etu;
        $this->n_event = $n_event;
    }

    public function getNom_etu() { return $this->nom_etu; }
    public function getPrenom_etu() { return $this->prenom_etu; }
    public function getNom_event() { return $this->nom_event; }
    public function getDateDebut() { return $this->dateDebut; }
    public function getPrix() { return $this->prix; }
    public function getA_payer(): bool {
        return $this->a_payer;
    }    
    public function getIdEvent() { return $this->n_event; }
    public function getIdAdherent() { return $this->n_etu; }
    public function getIdParticipation() { return $this->idParticipation;}
}
