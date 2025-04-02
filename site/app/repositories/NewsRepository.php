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
}
