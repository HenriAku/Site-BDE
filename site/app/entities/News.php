<?php

class News {
    private $id;
    private $titre;
    private $contenu;
    private $date;

    public function __construct($id, $titre, $date, $contenu) {
        $this->id = $id;
        $this->titre = $titre;
        $this->date = $date;
        $this->contenu = $contenu;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getDate() { return $this->date; }
    public function getContenu() { return $this->contenu; }
}
