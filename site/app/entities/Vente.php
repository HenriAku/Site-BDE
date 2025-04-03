<?php

class Vente {
    private $nom_etu;
    private $prenom_etu;
    private $libelle_prod;
    private $mail_etu;
    private $prix_prod;
    private $estPayee;
    private $quantite_vente;
    private $n_prod;
    private $n_etu;
    private $n_event;

    public function __construct($nom_etu, $prenom_etu, $libelle_prod, $mail_etu, $prix_prod, $estPayee, $quantite_vente, $n_prod, $n_etu,$n_event ) {
        $this->nom_etu = $nom_etu;
        $this->prenom_etu = $prenom_etu;
        $this->libelle_prod = $libelle_prod;
        $this->mail_etu = $mail_etu;
        $this->prix_prod = $prix_prod;
        $this->estPayee = $estPayee ?? false;
        $this->quantite_vente = $quantite_vente;
        $this->n_prod = $n_prod;
        $this->n_etu = $n_etu;
        $this->n_event = $n_event;
    }

    public function getNom_etu() { return $this->nom_etu; }
    public function getPrenom_etu() { return $this->prenom_etu; }
    public function getLibelle_prod() { return $this->libelle_prod; }
    public function getMail_etu() { return $this->mail_etu; }
    public function getPrix_prod() { return $this->prix_prod; }
    public function getEstPayee() :bool{ return $this->estPayee; }
    public function getQuantite_vente() { return $this->quantite_vente; }
    public function getN_prod() { return $this->n_prod; }
    public function getN_etu() { return $this->n_etu; }
    public function getN_event() { return $this->n_event; }
}
