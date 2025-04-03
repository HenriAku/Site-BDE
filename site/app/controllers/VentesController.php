<?php

require_once './app/services/AuthService.php';
require_once './app/repositories/VenteRepository.php';
require_once './app/core/Controller.php';

class VentesController extends Controller {

    public function afficherVentes() {

        $venteRepo = new VenteRepository();
        $ventes = $venteRepo->findAll();

        $servUser = new AuthService();
        if($servUser->getUser() === null)
        {
            $this->view('/boutique/ventes.html.twig', ['ventes' => $ventes,'admin' => $perm]);
        }else{
            $user = $servUser->getUser();
            $perm = $user->getAdmin();
            $this->view('/boutique/ventes.html.twig', ['ventes' => $ventes,'admin' => $perm]);
        }
        
    }

    public function validerVentes() {
        
        $venteRepo = new VenteRepository();
        $idVente = $_POST['idVente'] ?? null;
    
        if (!$idVente) {
            $this->view('/boutique/ventes.html.twig', [
                'ventes' => $venteRepo->findAll(),
                'error' => 'Identifiants manquants'
            ]);
            return;
        }
        
        $success = $venteRepo->validerVentes($idVente);

        $servUser = new AuthService();
        if($servUser->getUser() === null)
        {
            $this->view('/boutique/ventes.html.twig', ['ventes' => $venteRepo->findAll(),'admin' => null]);
        }else{
            $user = $servUser->getUser();
            $perm = $user->getAdmin();
            $this->view('/boutique/ventes.html.twig', ['ventes' => $venteRepo->findAll(),'admin' => $perm]);
        } 
    }  
}