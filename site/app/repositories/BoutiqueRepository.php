<?php
require_once './app/core/Repository.php';
require_once './app/entities/Produit.php';

class ProduitRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }
}