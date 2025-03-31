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
}
