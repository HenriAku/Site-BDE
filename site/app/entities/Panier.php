<?php
class Panier {

    public function __construct
    (
        public int $n_panier,
        public int $n_produit,
        public int $n_user,
        public string $taille,
        public string $couleur,
        public int $qte
    )
    {}

    public function getn_panier(): ?int {
        return $this->n_panier;
    }

    public function getn_produit(): int {
        return $this->n_produit;
    }

    public function setn_produit(int $n_produit): void {
        $this->n_produit = $n_produit;
    }

    public function getn_user(): int {
        return $this->n_user;
    }

    public function setn_user(int $n_user): void {
        $this->n_user = $n_user;
    }

    public function getqte(): int {
        return $this->qte;
    }

    public function setqte(int $qte): void {
        $this->qte = $qte;
    }

    public function gettaile(): string {
        return $this->taille;
    }

    public function settaille(string $taille): void {
        $this->taille = $taille;
    }

    public function getcouleur(): string {
        return $this->couleur;
    }

    public function setcouleur(string $couleur): void {
        $this->couleur = $couleur;
    }

}