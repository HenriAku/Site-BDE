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

    public function create(Produit $Produit): int {
        $stmt = $this->pdo->prepare('
            INSERT INTO Produit (libelle_prod, stock_prod, categorie_prod, prix_prod, description_prod, couleur_prod, taille_prod)
            VALUES (:name, :stock, :category, :price, :description, :color, :size)
        ');

        $stmt->execute([
            'name' => $Produit->getName(),
            'stock' => $Produit->getStock(),
            'category' => $Produit->getCategory(),
            'price' => $Produit->getPrice(),
            'description' => $Produit->getDescription(),
            'color' => $Produit->getColor(),
            'size' => $Produit->getSize()
        ]);

        return $this->pdo->lastInsertId();
    }

    public function createProduitFromRow(array $row): Produit 
    {
        return new Produit(
            $row['n_prod'],
            $row['libelle_prod'],
            $row['stock_prod'],
            $row['categorie_prod'],
            $row['prix_prod'],
            $row['description_prod'],
            $row['couleur_prod'],
            $row['taille_prod']
        );
    }

    public function findById(int $id): ?Produit {
        $stmt = $this->pdo->prepare('SELECT * FROM Produit WHERE n_prod = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ? $this->createProduitFromRow($row) : null;
    }

    public function getImg(): array {
        $stmt = $this->pdo->query('SELECT * FROM contient_produit');
        $images = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $images[] = [
                'nom_image' => $row['nom_image'],
                'n_prod' => $row['n_prod']
            ];
        }
        
        return $images;
    }

    public function updateProduit(Produit $Produit, int $id): bool {
        error_log("Tentative de mise à jour du produit ID: " . $id);
        error_log("Données du produit: " . print_r([
            'name' => $Produit->getName(),
            'stock' => $Produit->getStock(),
            'category' => $Produit->getCategory(),
            'price' => $Produit->getPrice(),
            'description' => $Produit->getDescription(),
            'color' => $Produit->getColor(),
            'size' => $Produit->getSize()
        ], true));
    
        $query = "UPDATE produit SET
                 libelle_prod = :nom,
                 stock_prod = :stock,
                 categorie_prod = :categorie,
                 prix_prod = :prix,
                 description_prod = :description,
                 couleur_prod = :color,
                 taille_prod = :size
                 WHERE n_prod = :id";
    
        $stmt = $this->pdo->prepare($query);
    
        $params = [
            ':nom' => $Produit->getName(),
            ':stock' => $Produit->getStock(),
            ':categorie' => $Produit->getCategory(),
            ':prix' => $Produit->getPrice(),
            ':description' => $Produit->getDescription(),
            ':color' => $Produit->getColor(),
            ':size' => $Produit->getSize(),
            ':id' => $id
        ];
    
        $result = $stmt->execute($params);
        
        if (!$result) {
            error_log("Erreur SQL: " . print_r($stmt->errorInfo(), true));
            return false;
        }
    
        error_log("Mise à jour réussie, lignes affectées: " . $stmt->rowCount());
        return true;
    }
    

    public function deleteProduit(int $id): bool {
        $this->pdo->beginTransaction();
        
        try {  
            $this->deleteProductImage($id);
            
            $stmt = $this->pdo->prepare("DELETE FROM Produit WHERE n_prod = :id");
            $stmt->execute([':id' => $id]);
            
            $this->pdo->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erreur suppression produit: " . $e->getMessage());
            return false;
        }
    }

    private function deleteProductImage(int $id): void {
        $stmt = $this->pdo->prepare("SELECT nom_image FROM contient_produit WHERE n_prod = :id");
        $stmt->execute([':id' => $id]);
        $image = $stmt->fetchColumn();
        
        if ($image) {
            $filePath = __DIR__ . '/../../public/asset/images/produit/' . $image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM contient_produit WHERE n_prod = :id");
            $stmt->execute([':id' => $id]);
        }
    }

    public function saveImage(int $productId, string $imageName): bool {
        $stmt = $this->pdo->prepare('
            INSERT INTO contient_produit (n_prod, nom_image)
            VALUES (:productId, :imageName)
            ON DUPLICATE KEY UPDATE nom_image = :imageName
        ');
        
        return $stmt->execute([
            'productId' => $productId,
            'imageName' => $imageName
        ]);
    }
}