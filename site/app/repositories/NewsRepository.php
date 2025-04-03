<?php

require_once './app/entities/News.php';

class NewsRepository {
    
    private $db;

    public function __construct() {
        $this->db = Repository::getInstance()->getPDO();
    }

    public function findAll() {
        $query = "SELECT 
                    n_art AS id, 
                    titre_art AS titre, 
                    contenu_art AS contenu, 
                    date_publi_art AS date 
                  FROM Article 
                  ORDER BY date_publi_art DESC";
    
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $news = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $news[] = new News(
                $row['id'],
                $row['titre'],
                $row['contenu'],
                $row['date']
            );
        }
        return $news;
    }

    public function create (
        string $nom, 
        string $contenu
    ) : bool {
        $this->db->beginTransaction();

        try {
            // 1. Insérer l'événement
            $query = "INSERT INTO Article 
                     (titre_art, contenu_art)
                     VALUES (:titre, :contenu)";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':titre' => $nom,
                ':contenu' => $contenu
            ]);

            $this->db->commit();

            return true;

        }catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur création événement: " . $e->getMessage());
            return false;
        }
            
    }

    public function update(
        int $id,
        string $titre, 
        string $contenu
    ): bool {
        try {
            $query = "UPDATE Article SET
                     titre_art = :titre,
                     contenu_art = :contenu
                     WHERE n_art = :id";
            
            $stmt = $this->db->prepare($query);
            $params = [
                ':titre' => $titre,
                ':contenu' => $contenu,
                ':id' => $id
            ];
 
            $stmt->execute($params);
            return true;
        } catch (Exception $e) {
            error_log("Erreur mise à jour événement: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool {
        if (!$this->db) {
            throw new Exception('Connexion PDO non initialisée');
        }
        $stmt = $this->db->prepare('DELETE FROM Article WHERE n_art = :id');
        $stmt->execute(['id' => $id]);
        $rowsAffected = $stmt->rowCount();
        return $rowsAffected > 0;
    }

}
