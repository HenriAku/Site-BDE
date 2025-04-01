<?php

require_once './app/entities/Evenement.php';

class EvenementRepository {
    
    private $db;

    public function __construct() {
        $this->db = Repository::getInstance()->getPDO();
    }

    public function findAll() {
        // Requête pour récupérer les événements avec leur note moyenne
        $query = "SELECT e.*, COALESCE(AVG(c.note), 0) as note_moyenne 
                  FROM evenement e
                  LEFT JOIN Commente c ON e.n_event = c.n_event
                  GROUP BY e.n_event
                  ORDER BY e.date_debut_event DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $evenements = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $evenement = new Evenement(
                $row['n_event'],
                $row['nom_event'],
                $row['date_debut_event'],
                $row['description_event'],
                $row['adr_event'],
                $row['prix_event']
            );
            
            // Ajoutez la note moyenne à l'objet Evenement
            $evenement->note_moyenne = round($row['note_moyenne'], 1);
            
            $evenements[] = $evenement;
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

    public function findByIdWithAverageRating(int $id) {
        $query = "SELECT e.*, 
                  COALESCE(AVG(c.note), 0) as note_moyenne,
                  COUNT(c.note) as nb_avis
                  FROM evenement e
                  LEFT JOIN Commente c ON e.n_event = c.n_event
                  WHERE e.n_event = :id
                  GROUP BY e.n_event";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$row) {
            return null;
        }
    
        $event = new Evenement(
            $row['n_event'],
            $row['nom_event'],
            $row['date_debut_event'],
            $row['description_event'],
            $row['adr_event'],
            $row['prix_event']
        );
        
        $event->note_moyenne = round($row['note_moyenne'], 1);
        $event->nb_avis = $row['nb_avis'];
        
        return $event;
    }
    
}
