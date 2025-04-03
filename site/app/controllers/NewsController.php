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
        
        $servUser = new AuthService();
        if($servUser->getUser() === null)
        {
            $this->view('news/ajouter_news.html.twig', [
                'news' => $news,
                'messages' => $messages,
                'admin' => null
            ]);

        }else{
            $user = $servUser->getUser();
            $perm = $user->getAdmin();

            $this->view('news/ajouter_news.html.twig', [
                'news' => $news,
                'messages' => $messages,
                'admin' => $perm
            ]);
        }

    }

    public function addNews(){
        $action = $_POST['action'] ?? '';
        $newsRepo = new NewsRepository();
        
        switch ($action) {
            case 'add':
                $newsRepo->create($_POST['titre'], $_POST['contenu']);
                break;
                
            case 'update':
                $newsRepo->update($_POST['id'], $_POST['titre'], $_POST['contenu']);
                break;
                
            case 'delete':
                $newsRepo->delete($_POST['id']);
                break;
        }
        
        $this->redirectTo('/ajouter_news.php');
    }

    
}