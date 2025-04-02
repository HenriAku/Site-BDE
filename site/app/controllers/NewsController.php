<?php

require_once './app/services/AuthService.php';
require_once './app/repositories/NewsRepository.php';
require_once './app/core/Controller.php';

class Newscontroller extends Controller {

    public function creerNews() {

        $newsRepo = new NewsRepository();
        $news = $newsRepo->findAll();
        $messages = [];
        if (isset($_SESSION['messages'])) {
            $messages = $_SESSION['messages'];
            unset($_SESSION['messages']);
        }

        $this->view('news/ajouter_news.html.twig', [
            'news' => $news,
            'messages' => $messages
        ]);

    }

    public function addNews(){
        $action = $_POST['action'] ?? '';
        $newsRepo = new NewsRepository();
        
        switch ($action) {
            case 'add':
                // Ajouter une nouvelle news
                $newsRepo->create($_POST['titre'], $_POST['contenu']);
                $_SESSION['messages']['success'] = "News ajoutée avec succès";
                break;
                
            case 'update':
                // Mettre à jour une news existante
                $newsRepo->update($_POST['id'], $_POST['titre'], $_POST['contenu']);
                $_SESSION['messages']['success'] = "News mise à jour avec succès";
                break;
                
            case 'delete':
                // Supprimer une news
                $newsRepo->delete($_POST['id']);
                $_SESSION['messages']['success'] = "News supprimée avec succès";
                break;
        }
        
        $this->redirectTo('/ajouter_news.php');
    }
}