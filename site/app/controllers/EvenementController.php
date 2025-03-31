<?php

require_once './app/services/AuthService.php';
require_once './app/repositories/EvenementRepository.php';
require_once './app/core/Controller.php';

class EvenementController extends Controller {

    public function index() {
        //$this->checkAuth();

        $evenementRepo = new EvenementRepository();
        $evenements = $evenementRepo->findAll();

        $this->view('/evenement/index.html.twig', ['evenements' => $evenements]);

    }

    private function checkAuth() {
        //$auth = new AuthService();
        if (!$auth->isLoggedIn()) {
            $this->redirectTo('login.php');
        }
    }
    public function show() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new Exception("ID invalide");
        }

        echo("blablabla");
    
        $id = (int) $_GET['id'];
        $repo = new EvenementRepository();
        $event = $repo->findById($id);
    
        if (!$event) {
            throw new Exception("Événement non trouvé");
        }
    
        $this->view('evenement/show.html.twig', [
            'event' => $event
        ]);
    }
}
