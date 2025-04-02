<?php

require_once './app/entities/Participation.php';

class ParticipationRepesitory {
    
    private $db;

    public function __construct() {
        $this->db = Repository::getInstance()->getPDO();
    }

    public function findAll() {
        $query = "SELECT a.nom_etu,a.prenom_etu,e.nom_event, e.date_debut_event, e.prix_event, p.a_payer, a.n_etu, e.n_event
              FROM Participe p
              LEFT JOIN Adherent a ON a.n_etu = p.n_etu
              LEFT JOIN Evenement e ON e.n_event = p.n_event";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $participations = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $participation = new Participation(
                $row['nom_etu'],
                $row['prenom_etu'],
                $row['nom_event'],
                $row['date_debut_event'],
                $row['prix_event'],
                $row['a_payer'],
                $row['n_etu'],
                $row['n_event']
            );
                        
            $participations[] = $participation;
        }
        return $participations;
    }

    public function payerParticipation(int $idEvent, int $idEtu): bool {
        try {
            
            $query = "UPDATE Participe SET a_payer = true WHERE n_event = :idEvent AND n_etu = :idEtu";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([':idEvent' => $idEvent, ':idEtu' => $idEtu]);
            
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
}