<?php

require_once './app/entities/Evenement.php';

class EvenementRepository {
    
    private $db;

    public function __construct() {
        $this->db = Repository::getInstance()->getPDO();
    }

    public function findAll() {
        // Requête pour récupérer les événements avec leur note moyenne
        $query = "SELECT e.*, 
              COALESCE(AVG(c.note), 0) as note_moyenne,
              COUNT(c.note) as nb_avis,
              f.nom_image as image
              FROM evenement e
              LEFT JOIN Commente c ON e.n_event = c.n_event
              LEFT JOIN contient_evenement ce ON e.n_event = ce.n_event
              LEFT JOIN Fichier f ON ce.nom_image = f.nom_image
              GROUP BY e.n_event, f.nom_image
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
                $row['prix_event'],
                $row['image']
            );
            
            // Ajoutez la note moyenne à l'objet Evenement
            $evenement->note_moyenne = round($row['note_moyenne'], 1);
            
            $evenements[] = $evenement;

            
        }
    
        return $evenements;
    }

    public function findById(int $id) {
        $query = "SELECT e.*, f.nom_image as image
                  FROM evenement e
                  LEFT JOIN contient_evenement ce ON e.n_event = ce.n_event
                  LEFT JOIN Fichier f ON ce.nom_image = f.nom_image
                  WHERE e.n_event = :id
                  LIMIT 1";
        
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
            $row['prix_event'],
            $row['image']
        );
    }

    public function findByIdWithComments(int $id) {
        // Requête pour l'événement avec stats
        $queryEvent = "SELECT e.*, 
                      COALESCE(AVG(c.note), 0) as note_moyenne,
                      COUNT(DISTINCT c.n_etu) as nb_avis,
                      f.nom_image as image
                      FROM evenement e
                      LEFT JOIN Commente c ON e.n_event = c.n_event
                      LEFT JOIN contient_evenement ce ON e.n_event = ce.n_event
                      LEFT JOIN Fichier f ON ce.nom_image = f.nom_image
                      WHERE e.n_event = :id
                      GROUP BY e.n_event, e.nom_event, e.date_debut_event, 
                               e.description_event, e.adr_event, e.prix_event, f.nom_image";
        
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
            $eventData['prix_event'],
            $eventData['image']
        );
        
        $event->note_moyenne = round($eventData['note_moyenne'], 1);
        $event->nb_avis = $eventData['nb_avis'];
    
        // Requête des commentaires (inchangée)
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
            'comments' => $comments
        ];
    }
}
