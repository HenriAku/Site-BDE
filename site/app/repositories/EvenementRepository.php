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

    public function addComment(int $eventId, int $userId, int $note, string $avis): bool {
        $query = "INSERT INTO Commente (n_event, n_etu, note, avis) 
                  VALUES (:eventId, :userId, :note, :avis)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':note', $note, PDO::PARAM_INT);
        $stmt->bindParam(':avis', $avis, PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    public function findAllForAdmin(): array {
        $query = "SELECT e.*, 
                 COUNT(c.n_event) as nb_commentaires,
                 f.nom_image as image
                 FROM evenement e
                 LEFT JOIN Commente c ON e.n_event = c.n_event
                 LEFT JOIN contient_evenement ce ON e.n_event = ce.n_event
                 LEFT JOIN Fichier f ON ce.nom_image = f.nom_image
                 GROUP BY e.n_event
                 ORDER BY e.date_debut_event DESC";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(
        string $nom, 
        string $date, 
        string $description, 
        string $adresse, 
        float $prix,
        int $places,
        ?array $imageFile = null
    ): bool {
        $this->db->beginTransaction();
        
        try {
            // 1. Insérer l'événement
            $query = "INSERT INTO evenement 
                     (nom_event, date_debut_event, description_event, adr_event, prix_event)
                     VALUES (:nom, :date, :description, :adresse, :prix)";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':nom' => $nom,
                ':date' => $date,
                ':description' => $description,
                ':adresse' => $adresse,
                ':prix' => (int)$prix
            ]);
            
            $eventId = $this->db->lastInsertId();
            
            // 2. Gérer l'image si elle existe
            if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
                $filename = $this->handleImageUpload($imageFile, $eventId);
                
                // Insérer d'abord dans Fichier
                $query = "INSERT INTO Fichier (nom_image) VALUES (:filename)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([':filename' => $filename]);
                
                // Puis dans contient_evenement
                $query = "INSERT INTO contient_evenement (n_event, nom_image)
                         VALUES (:eventId, :filename)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    ':eventId' => $eventId,
                    ':filename' => $filename
                ]);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur création événement: " . $e->getMessage());
            return false;
        }
    }

    public function update(
        int $id,
        string $nom, 
        string $date, 
        string $description, 
        string $adresse, 
        float $prix,
        ?int $places = null,  // Rendre le paramètre optionnel
        ?array $image = null
    ): bool {
        try {
            // Requête UPDATE adaptée (sans places_disponibles)
            $query = "UPDATE evenement SET
                     nom_event = :nom,
                     date_debut_event = :date,
                     description_event = :description,
                     adr_event = :adresse,
                     prix_event = :prix
                     WHERE n_event = :id";
            
            $stmt = $this->db->prepare($query);
            $params = [
                ':nom' => $nom,
                ':date' => $date,
                ':description' => $description,
                ':adresse' => $adresse,
                ':prix' => (int)$prix, // Conversion en integer
                ':id' => $id
            ];
            
            if (!$stmt->execute($params)) {
                throw new RuntimeException("Erreur lors de la mise à jour");
            }
            
            // 2. Gérer l'image si fournie
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                // Supprimer l'ancienne image si elle existe
                $this->deleteEventImage($id);
                
                // Ajouter la nouvelle image
                $filename = $this->handleImageUpload($image, $id);
                
                $query = "INSERT INTO contient_evenement (n_event, nom_image)
                         VALUES (:eventId, :filename)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    ':eventId' => $id,
                    ':filename' => $filename
                ]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            error_log("Erreur mise à jour événement: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool {
        $this->db->beginTransaction();
        
        try {
            // 1. Supprimer les commentaires associés
            $query = "DELETE FROM Commente WHERE n_event = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            
            // 2. Supprimer les références aux images
            $this->deleteEventImage($id);
            
            // 3. Supprimer l'événement
            $query = "DELETE FROM evenement WHERE n_event = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            
            $this->db->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erreur suppression événement: " . $e->getMessage());
            return false;
        }
    }

    // Méthodes utilitaires privées
    private function handleImageUpload(array $imageFile, int $eventId): string
    {
        // Chemin vers le dossier des images (modifié)
        $uploadDir = __DIR__ . '/../../asset/images/evenements/';
        
        // Créer le dossier s'il n'existe pas
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $filename = 'event_' . $eventId . '_' . time() . '.' . $extension;
        
        if (!move_uploaded_file($imageFile['tmp_name'], $uploadDir . $filename)) {
            throw new RuntimeException("Échec de l'upload de l'image");
        }
        
        return $filename;
    }


    private function deleteEventImage(int $eventId): void {
        // 1. Récupérer le nom de l'image
        $query = "SELECT nom_image FROM contient_evenement WHERE n_event = :eventId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':eventId' => $eventId]);
        $image = $stmt->fetchColumn();
        
        if ($image) {
            // 2. Supprimer le fichier physique
            $filePath = __DIR__ . '/../../public/uploads/events/' . $image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // 3. Supprimer la référence en base
            $query = "DELETE FROM contient_evenement WHERE n_event = :eventId";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':eventId' => $eventId]);
        }
    }

    public function isAlreadyRegistered(int $userId, int $eventId): bool
    {
        $query = "SELECT COUNT(*) FROM Participe WHERE n_etu = :userId AND n_event = :eventId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':userId' => $userId,
            ':eventId' => $eventId
        ]);
        
        return (int)$stmt->fetchColumn() > 0;
    }

    public function registerForEvent(int $userId, int $eventId): bool
    {
        $query = "INSERT INTO Participe (n_etu, n_event) VALUES (:userId, :eventId)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':userId' => $userId,
            ':eventId' => $eventId
        ]);
    }
}
