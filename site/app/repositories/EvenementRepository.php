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

    public function findByIdWithComments(int $id) {
        // Requête pour l'événement avec sa note moyenne
        $queryEvent = "SELECT e.*, 
                      COALESCE(AVG(c.note), 0) as note_moyenne,
                      COUNT(DISTINCT c.n_etu) as nb_avis
                      FROM evenement e
                      LEFT JOIN Commente c ON e.n_event = c.n_event
                      WHERE e.n_event = :id
                      GROUP BY e.n_event";
        
        $stmtEvent = $this->db->prepare($queryEvent);
        $stmtEvent->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtEvent->execute();
        $eventData = $stmtEvent->fetch(PDO::FETCH_ASSOC);
    
        if (!$eventData) {
            return null;
        }
    
        $event = new Evenement(
            $eventData['n_event'],
            $eventData['nom_event'],
            $eventData['date_debut_event'],
            $eventData['description_event'],
            $eventData['adr_event'],
            $eventData['prix_event']
        );
        $event->note_moyenne = round($eventData['note_moyenne'], 1);
        $event->nb_avis = $eventData['nb_avis'];
    
        // Requête pour les commentaires
        $queryComments = "SELECT c.avis, c.note, 
                         a.prenom_etu, a.nom_etu
                         FROM Commente c
                         JOIN Adherent a ON c.n_etu = a.n_etu
                         WHERE c.n_event = :id
                         ORDER BY c.note DESC";
        
        $stmtComments = $this->db->prepare($queryComments);
        $stmtComments->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtComments->execute();
    
        $comments = [];
        while ($row = $stmtComments->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = [
                'prenom' => $row['prenom_etu'],
                'nom' => $row['nom_etu'],
                'avis' => $row['avis'],
                'note' => $row['note']
            ];
        }
    
        return [
            'event' => $event,
            'comments' => $comments,
            'nb_avis' => count($comments) // Nombre réel de commentaires
        ];
    }
}
