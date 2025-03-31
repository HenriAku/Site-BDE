<?php

require_once './app/entities/News.php';

class NewRepository {
    
    private $db;

    public function __construct() {
        $this->db = Repository::getInstance()->getPDO();
    }

    public function findAll() {
        $query = "SELECT * FROM Article ORDER BY date_publi_art DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        $news = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $new[] = new News(
                $row['n_event'],
                $row['nom_event'],
                $row['date_debut_event'],
                $row['description_event'],
            );
        }

        return $news;
    }
}
