<?php

require_once './app/entities/Evenement.php';

class EvenementRepository {
    
    private $db;

    public function __construct() {
        $this->db = Repository::getInstance()->getPDO();
    }

    public function findAll() {
        $query = "SELECT * FROM evenement ORDER BY date_debut_event DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $evenements = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $evenements[] = new Evenement(
                $row['n_event'],
                $row['nom_event'],
                $row['date_debut_event'],
                $row['description_event'],
                $row['adr_event'],
                $row['prix_event']
            );
        }

        return $evenements;
    }

    public function findById(int $id) {
        $query = "SELECT * FROM evenement WHERE n_event = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$row) {
            return null;
        }
    
        return new Evenement(
            $row['n_event'],
            $row['nom_event'],
            $row['date_debut_event'],
            $row['description_event'],
            $row['adr_event'],
            $row['prix_event']
        );
    }
    



}
