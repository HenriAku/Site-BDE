<?php

require_once './app/services/AuthService.php';
require_once './app/repositories/EvenementRepository.php';
require_once './app/core/Controller.php';

class EvenementController extends Controller {

    public function index() {
        //$this->checkAuth();

        $evenementRepo = new EvenementRepository();
        $evenements = $evenementRepo->findAll();

        $this->view('/evenement/eventIndex.html.twig', ['evenements' => $evenements]);

    }

    private function checkAuth() {
        $auth = new AuthService();
        if (!$auth->isLoggedIn()) {
            $this->redirectTo('login.php');
        }
    }
    public function show() {
        if (!isset($_GET['id'])) {
            die("ID invalide !");
        }
    
        $id = (int)$_GET['id'];
        $eventRepository = new EvenementRepository();
        $data = $eventRepository->findByIdWithComments($id);
    
        if (!$data['event']) {
            die("Événement non trouvé !");
        }
    
        $this->view('/evenement/show.html.twig', [
            'event' => $data['event'],
            'comments' => $data['comments']
        ]);
    }

    public function addComment() {
        $auth = new AuthService();
        if (!$auth->isLoggedIn()) {
            $this->redirectTo('login.php');
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = (int)$_GET['id'];
            $note = (int)$_POST['note'];
            $avis = htmlspecialchars($_POST['avis']);
            $userId = $_SESSION['user_id'];
    
            // Validation
            if ($note < 1 || $note > 5 || empty($avis)) {
                $_SESSION['error'] = "Veuillez donner une note entre 1 et 5 et écrire un commentaire";
                $this->redirectTo("/event.php?id=$eventId");
            }
    
            // Insertion en base
            $repo = new EvenementRepository();
            $success = $repo->addComment($eventId, $userId, $note, $avis);
    
            if ($success) {
                $_SESSION['success'] = "Votre commentaire a été ajouté !";
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du commentaire";
            }
    
            $this->redirectTo("/event.php?id=$eventId");
        }
    }
    
}
