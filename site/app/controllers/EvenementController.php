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
        //$auth = new AuthService();
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
    
}
