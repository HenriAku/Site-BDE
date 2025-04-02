<?php
require_once './app/core/Repository.php';
require_once './app/entities/Panier.php';
require_once './app/repositories/ProduitRepository.php';

class PanierRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM "detail_panier"');
        $d_panier = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            $d_panier[] = $this->createDPanierFromRow($row);
        }
        return $d_panier;
    }

    public function getProduit(array $paniers) : array
    {
        $prodRep = new ProduitRepository();
        $nomProd = [];
        for ($i = 0; $i < count($paniers); $i++)
        {
            $stmt = $this->pdo->prepare('SELECT * FROM "produit" WHERE n_prod = :id');
            $stmt->execute(['id' => $paniers[$i]->getn_produit()]);
            $nomProdRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $nomProd[] = $prodRep->createProduitFromRow($nomProdRow);  
        }

        return $nomProd;
    }

    private function createDPanierFromRow(array $row): Panier 
    {        
        return new Panier(
            $row['n_dp'],
            $row['n_prod'],
            $row['n_etu'],
            $row['quantite_dp']
        );
    }

    public function create(Panier $panier): bool 
    {
        $stmt = $this->pdo->prepare('INSERT INTO detail_panier (n_dp, n_prod, n_etu, quantite_dp) 
                                     VALUES (:n_dp, :n_prod, :n_etu, :quantite_dp)');
        return $stmt->execute([
            'n_dp' => $panier->getn_panier(),
            'n_prod' => $panier->getn_produit(),
            'n_etu' => $panier->getn_user(),
            'quantite_dp' => $panier->getqte()
        ]);
    }
    
    

    public function update(Panier $panier): bool
    {
        $stmt = $this->pdo->prepare('UPDATE detail_panier SET n_prod = :n_prod, n_etu = :n_etu, quantite_dp = :quantite_dp WHERE n_dp = :n_dp');
        return $stmt->execute([
            'n_prod' => $panier->getn_produit(),
            'n_etu' => $panier->getn_user(),
            'quantite_dp' => $panier->getqte(),
            'n_dp' => $panier->getn_panier()
        ]);
    }


    public function delete(int $id): bool {
        if (!$this->pdo) {
            throw new Exception('Connexion PDO non initialisée');
        }
        $stmt = $this->pdo->prepare('DELETE FROM detail_panier WHERE n_dp = :id');
        $stmt->execute(['id' => $id]);
        $rowsAffected = $stmt->rowCount();
        return $rowsAffected > 0;
    }

    public function findById(int $id): ?User {
        $stmt = $this->pdo->prepare('SELECT * FROM "detail_panier" WHERE n_dp = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return $this->createDPanierFromRow($user);
        }
        return null;
    }
}
