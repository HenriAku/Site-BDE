<?php

require_once './app/entities/Vente.php';

class VenteRepository {
    
    private $db;

    public function __construct() {
        $this->db = Repository::getInstance()->getPDO();
    }

    public function findAll() {
        $query = "SELECT etu.nom_etu,etu.prenom_etu,p.libelle_prod, etu.mail_etu
                , p.prix_prod, a.estPayee, a.quantite_vente, p.n_prod, etu.n_etu, a.n_vente
              FROM Achete a
              LEFT JOIN Adherent etu ON a.n_etu = etu.n_etu
              LEFT JOIN Produit p ON p.n_prod = a.n_prod";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $ventes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vente = new Vente(
                $row['nom_etu'],
                $row['prenom_etu'],
                $row['libelle_prod'],
                $row['mail_etu'],
                $row['prix_prod'],
                $row['estPayee'],
                $row['quantite_vente'],
                $row['n_prod'],
                $row['n_etu'],
                $row['n_vente']
            );
                        
            $ventes[] = $vente;
        }
        return $ventes;
    }

    public function validerVentes(int $idVente): bool {
        try {
            
            $query = "UPDATE Achete SET estPayee = true WHERE n_vente = :idVente";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([':idVente' => $idVente]);
            
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
}