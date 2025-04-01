<?php
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
            'color' => Produit->getColor(),
            'size' => Produit->getSize()
        ]);
    }

    private function createProduitFromRow(array $row): Produit
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

    
}
