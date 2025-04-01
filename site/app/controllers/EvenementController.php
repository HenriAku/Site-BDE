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
    
        $auth = new AuthService();
        $isLoggedIn = $auth->isLoggedIn();
        $currentUser = $isLoggedIn ? $auth->getUser() : null;

        $this->view('/evenement/show.html.twig', [
            'event' => $data['event'],
            'comments' => $data['comments'],
            'user' => $currentUser
        ]);
    }

    public function addComment() {
        $auth = new AuthService();
        
        // Vérifier la connexion
        if (!$auth->isLoggedIn()) {
            $_SESSION['error'] = "Vous devez être connecté pour commenter";
            header("Location: /login.php");
            exit();
        }
    
        // Récupérer l'utilisateur
        $user = $auth->getUser();
        if (!$user) {
            $_SESSION['error'] = "Problème de session utilisateur";
            header("Location: /login.php");
            exit();
        }
    
        $eventId = (int)$_GET['id'];
        $userId = $user->getId(); // Utilisez la méthode getId() de l'objet User
    
        // Vérification des données POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $note = (int)($_POST['note'] ?? 0);
            $avis = htmlspecialchars($_POST['avis'] ?? '');
    
            // Validation
            if ($note < 1 || $note > 5 || empty($avis)) {
                $_SESSION['error'] = "Note invalide ou commentaire vide";
                header("Location: /event.php?id=$eventId");
                exit();
            }
    
            // Ajout du commentaire
            $repo = new EvenementRepository();
            if ($repo->addComment($eventId, $userId, $note, $avis)) {
                $_SESSION['success'] = "Commentaire ajouté avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du commentaire";
            }
    
            header("Location: /event.php?id=$eventId");
            exit();
        }
    }
    
}
