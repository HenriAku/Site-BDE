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

    public function createEvent()
    {
        $auth = new AuthService();
        if (!$auth->isLoggedIn()) {
            $this->redirectTo('login.php');
        }

        $repo = new EvenementRepository();
        $events = $repo->findAll();

        // Gérer les soumissions du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = $_POST['id'] ?? 0;

            switch ($action) {
                case 'add':
                    $this->addEvent($repo);
                    break;
                case 'update':
                    $this->updateEvent($repo, $id);
                    break;
                case 'delete':
                    $this->deleteEvent($repo, $id);
                    break;
            }

            // Rediriger pour éviter la resoumission du formulaire
            $this->redirectTo('/create_event.php');
        }

        // Afficher la page avec la liste des événements
        $this->view('/evenement/create_event.html.twig', [
            'events' => $events,
            'messages' => $_SESSION['user'] ?? []
        ]);

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

    public function adminPanel()
    {
        $auth = new AuthService();
        if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
            $this->redirectTo('login.php');
        }

        $repo = new EvenementRepository();
        $events = $repo->findAllForAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'add':
                    $this->handleAddEvent($repo);
                    break;
                case 'update':
                    $id = $_POST['id'] ?? 0;
                    $this->handleUpdateEvent($repo, $id);
                    break;
                case 'delete':
                    $id = $_POST['id'] ?? 0;
                    $this->handleDeleteEvent($repo, $id);
                    break;
            }
            
            $this->redirectTo('/create_produit.php');
        }

        $this->view('/evenement/create_event.html.twig', [
            'events' => $events,
            'messages' => $_SESSION['admin_messages'] ?? []
        ]);
        
        unset($_SESSION['user']);
    }

    private function handleAddEvent(EvenementRepository $repo)
    {
        $data = $this->validateEventData($_POST);
        
        if ($repo->create(
            $data['nom'],
            $data['date'],
            $data['description'],
            $data['adresse'],
            $data['prix'],
            $data['places'],
            $_FILES['image']
        )) {
            $_SESSION['admin_messages']['success'] = "Événement ajouté avec succès";
        } else {
            $_SESSION['admin_messages']['error'] = "Erreur lors de l'ajout de l'événement";
        }
    }

    private function validateEventData(array $postData): array
    {
        return [
            'nom' => htmlspecialchars(trim($postData['nom'] ?? '')),
            'date' => $postData['date'] ?? '',
            'description' => htmlspecialchars(trim($postData['description'] ?? '')),
            'adresse' => htmlspecialchars(trim($postData['adresse'] ?? '')),
            'prix' => (float)($postData['prix'] ?? 0),
        ];
    }

    private function addEvent(EvenementRepository $repo)
    {
        try {
            $data = $this->validateEventData($_POST);
            
            $prix = (int)round($data['prix']);
            
            if ($repo->create(
                $data['nom'],
                $data['date'],
                $data['description'],
                $data['adresse'],
                $prix,
                0,
                $_FILES['image']
            )) {
            } else {
                throw new RuntimeException("Erreur lors de la création");
            }
        } catch (Exception $e) {
            
        }
    }

    private function updateEvent(EvenementRepository $repo, int $id)
    {
        try {
            $data = $this->validateEventData($_POST);
            
            // Fournir une valeur par défaut pour places
            $places = (int)($_POST['places'] ?? 0);
            
            if ($repo->update(
                $id,
                $data['nom'],
                $data['date'],
                $data['description'],
                $data['adresse'],
                $data['prix'],
                $places, // Maintenant toujours un int
                $_FILES['image']
            )) {
                $_SESSION['admin_messages']['success'] = "Événement mis à jour";
            } else {
                throw new RuntimeException("Erreur lors de la mise à jour");
            }
        } catch (Exception $e) {
            $_SESSION['admin_messages']['error'] = $e->getMessage();
        }
    }

    private function deleteEvent(EvenementRepository $repo, int $id)
    {
        if ($repo->delete($id)) {
            $_SESSION['admin_messages']['success'] = "Événement supprimé";
        } else {
            $_SESSION['admin_messages']['error'] = "Erreur lors de la suppression";
        }
    }
    
}
