<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once './app/core/Repository.php';
require_once './app/entities/Produit.php';

class ProduitRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM Produit');
        $Produits = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $Produits[] = $this->createProduitFromRow($row);
        }
        return $Produits;
    }

    public function create(Produit $Produit): bool {
        $stmt = $this->pdo->prepare('
        INSERT INTO Produit (name, stock, category, price, description, color, size)
        VALUES (:name, :stock, :category, :price, :description, :color, :size)
    ');

        return $stmt->execute([
            'name' => $Produit->getName(),
            'stock' => $Produit->getStock(),
            'category' => $Produit->getCategory(),
            'price' => $Produit->getPrice(),
            'description' => $Produit->getDescription(),
            'color' => $Produit->getColor(),
            'size' => $Produit->getSize()
        ]);
    }

    public function createProduitFromRow(array $row): Produit
    {
        return new Produit($row['n_prod'], $row['libelle_prod'], $row['stock_prod'], $row['categorie_prod'], $row['prix_prod'], $row['description_prod'], $row['couleur_prod'], $row['taille_prod']);
    }

    public function findById(int $id): ?Produit
    {
        $stmt = $this->pdo->prepare('SELECT * FROM Produit WHERE n_produit = :n_produit');
        $stmt->execute(['n_produit' => $n_produit]);
        $Produit = $stmt->fetch(PDO::FETCH_ASSOC);
        if($Produit)
            return $this->createProduitFromRow($Produit);
        return null;
    }

    public function getImg(): array
    {
        $query = 'SELECT * FROM contient_produit';
        $stmt = $this->pdo->prepare($query); // Utilisation correcte de $this->pdo
        $stmt->execute();
    
        $images = []; // Tableau pour stocker les tuples
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Ajoute chaque tuple au tableau
            $images[] = [
                'nom_image' => $row['nom_image'], // Utilisation correcte des clés
                'n_prod' => $row['n_prod']
            ];
        }
    
        return $images; // Retourne le tableau des tuples
    }

    public function updateProduit(Produit $Produit, int $id): bool
    {
        try {
            $query = "UPDATE Produit SET
                     libelle_prod = :nom,
                     stock_prod = :stock,
                     categorie_prod = :categorie,
                     prix_prod = :prix,
                     description_prod = :description,
                     couleur_prod = :coul,
                     taille_prod = :taille
                     WHERE n_prod = :id";
            
            $stmt = $this->pdo->prepare($query); // Correction: utiliser $this->pdo
            $params = [
                ':nom' => $Produit->getName(),
                ':stock' => $Produit->getStock(),
                ':categorie' => $Produit->getCategory(),
                ':prix' => $Produit->getPrice(),
                ':description' => $Produit->getDescription(), 
                ':coul' => $Produit->getColor(),
                ':taille' => $Produit->getSize(),
                ':id' => $id
            ];
            
            return $stmt->execute($params); // Retourne directement le résultat
            
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour: " . $e->getMessage());
            return false;
        }
    }
}
