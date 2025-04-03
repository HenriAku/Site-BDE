<?php
require_once './app/core/Repository.php';
require_once './app/services/AuthService.php';
require_once './app/repositories/UserRepository.php'; 

class AchatProduitRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }

    public function addPanier(string $taille, int $quantite, string $couleur, int $idProd ){
        $service = new AuthService();

        $user = $service->getUser();
        $userId = $user->getId();

        $sql = "INSERT INTO Detail_panier (n_prod, n_etu, taille_prod, couleur_prod, quantite_dp) VALUES (:idProd, :userId, :taille, :couleur, :quantite)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':idProd' => $idProd,
            ':userId' => $userId,
            ':taille' => $taille,
            ':couleur' => $couleur,
            ':quantite' => $quantite
        ]);
    }
}