<?php

require_once './app/services/AuthService.php';
require_once './app/repositories/EvenementRepository.php';
require_once './app/core/Controller.php';

class ShowController extends Controller {

    public function index($id) {
        //$this->checkAuth();

        $evenementRepo = new EvenementRepository();
        $evenement = $evenementRepo->findById($id);

        $this->view('/evenement/show.html.twig', ['evenements' => $evenements]);
    }
}
